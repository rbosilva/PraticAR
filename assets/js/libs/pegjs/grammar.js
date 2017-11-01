{
    // Funções e variáveis utilizadas pelo Parser
    var selects = 1;
    
    function simpleSelect(str) {
        if (str.search("SELECT") == 0) {
            return str;
        }
        return "SELECT * FROM " + str;
    }
    
    function selectAlias(op, cond, cols, rel) {
        if (rel.search("SELECT") === 0) {
            rel = "(" + rel + ")alias" + selects++;
        }
        var query;
        if (op === "π") {
            query = "SELECT " + cols + " FROM " + rel;
        } else if (op === "σ") {
            query = "SELECT * FROM " + rel + " WHERE " + cond;
        } else if (op === "both") {
            query = "SELECT " + cols + " FROM " + rel + " WHERE " + cond;
        } else {
            throw new Error("O parâmetro op tem de ser \"π\", \"σ\" ou \"both\".");
        }
        return query;
    }

    function renameRelCol(newName, text, type = "relation") {
        if (type === "column") {
            if ((text.search("SELECT") !== 0) || (text.search("SELECT \\*") === 0)) {
                throw new Error("Só é possível renomear Projeções.");
            }
            var oldNames = text.split(" ")[1].split(",");
            var newNames = newName.split(",");
            if (oldNames.length != newNames.length) {
                throw new Error("A Projeção deve possuir o mesmo número de colunas que a Renomeação.");
            }
            var str = "";
            for (var i = 0; i < oldNames.length; i++) {
                str += (oldNames[i] + " AS " +  newNames[i]);
                if (i + 1 < oldNames.length) {
                    str += ",";
                }
            }
            var queryPos = text.search(" FROM");
            return "SELECT " + str + text.substr(queryPos);
        } else {
            if (text.search("SELECT") === 0) {
                return "(" + text + ")" + newName;
            } else {
                return text + " AS " + newName;
            }
        }
    }
    
    function createJoin(rel1, rel2, col1, col2, op) {
        if (col1 == null || col2 == null) {
            var on = "";
        } else {
            var on = " ON " + col1 + " = " + col2;
        }
        if (rel1.trim().search("SELECT") === 0) {
            rel1 = "(" + rel1 + ")alias" + selects++;
        }
        if (rel2.trim().search("SELECT") === 0) {
            rel2 = "(" + rel2 + ")alias" + selects++;
        }
        return rel1 + op + rel2 + on;
    }
    
    function createIntersection(rel1, rel2, op = 'INTERSECT') {
        if ((rel1.search("SELECT") !== 0) || (rel1.search("SELECT \\*") === 0) ||
            (rel2.search("SELECT") !== 0) || (rel2.search("SELECT \\*") === 0)) {
            throw new Error("A operação de " + (op == 'EXCEPT' ? 'Diferença' : 'Intersecção') + " se aplica apenas a Projeções.");
        }
        var explode_rel1 = rel1.split(" "),
            cols_rel1 = explode_rel1[1].split(",");
        var explode_rel2 = rel2.split(" "),
            cols_rel2 = explode_rel2[1].split(",");
        if (cols_rel1.length != cols_rel2.length){
            throw new Error("As Projeções devem possuir o mesmo número de colunas.");
        }
        var str = '';
        for (var i = 0; i < cols_rel1.length; i++) {
            str += /*explode_rel1[3] + '.' +*/ cols_rel1[i] + ' = ' + /*explode_rel2[3] + '.' +*/ cols_rel2[i];
            if (i + 1 < cols_rel1.length) {
                str += ',';
            }
        }
        return rel1 + (rel1.search("WHERE") === -1 ? ' WHERE ' : ' AND ') +
               (op == 'EXCEPT' ? 'NOT ' : '') + 'EXISTS(' + rel2 +
               (rel2.search("WHERE") === -1 ? ' WHERE ' : ' AND ') + str + ')';
    }
    
    function createDivision(columns, rel1, rel2, cond) {
        columns = columns || "";
        var cols = columns.split(","),
            column_group = cols[0] == "*" ? "1" : cols[0];
        return "SELECT " + columns +
               " FROM " + rel1 + " JOIN " + rel2 + " ON " + cond +
               " GROUP BY " + column_group +
               " HAVING COUNT(*) = (SELECT COUNT(*) FROM " + rel2 + ")";
    }

}

// Aqui é onde são definidas as instruções do Parser

start
 = DivisionRelation
 / UnionExceptIntersectRelation

// União, Diferença, Intersecção
UnionExceptIntersectRelation
 = _ rel1:Relation _ op:Intersect _ rel2:Relation
   {return createIntersection(simpleSelect(rel1), simpleSelect(rel2), op);}
 / _ rel1:Relation _ op:Except _ rel2:Relation
   {return createIntersection(simpleSelect(rel1), simpleSelect(rel2), op);}
 / _ rel:Relation _ op:Union _ rest:UnionExceptIntersectRelation
   {return simpleSelect(rel) + op + rest;}
 / rel:Relation {return simpleSelect(rel)}
 
DivisionRelation
 = _ "π" _ columns:Columns _ "(" _ rel1:Relation _ ")" _ DivisionSymbol _ "(" _ cond:ConditionStart _ ")" _ rel2:Relation _
   {return createDivision(columns, rel1, rel2, cond);}
 / _ rel1:Relation _ DivisionSymbol _ "(" _ cond:ConditionStart _ ")" _ rel2:Relation _
   {return createDivision("*", rel1, rel2, cond);}

Relation
  // Projeção
 = _ "π" _ col:Columns _ "("_ "σ" _ cond:ConditionStart _ "(" _ rel:Relation _ ")" _ ")" _
   {return selectAlias("both", cond, col, rel);}
 / _ "π" _ col:Columns _ "(" _ rel:Relation _ ")" _
   {return selectAlias("π", null, col, rel);}
 / _ "π" _ col:Columns _ "(" _ rel:UnionExceptIntersectRelation _ ")" _
   {return selectAlias("π", null, col, rel);}
 / _ "π" _ col:Columns _ "(" _ rel:DivisionRelation _ ")" _
   {return selectAlias("π", null, col, rel);}
 // Seleção
 / _ "σ" _ cond:ConditionStart _ "(" _ rel:Relation _ ")" _
   {return selectAlias("σ", cond, null, rel);}
 // Produto Cartesiano
 / _ table:Identifier _ op:CrossJoinSymbol _ rel:Relation _
   {return table + op + rel}
 // Inner join
 / _ nome1:Identifier _ op:JoinSymbol _ "(" _ col1:SuperIdentifier _ "=" _ col2:SuperIdentifier _ ")" _ rel:Relation _
   {return createJoin(nome1, rel, col1, col2, op);}
 // Natural join
 / _ nome1:Identifier _ op:JoinSymbol _ rel:Relation _
   {return createJoin(nome1, rel, null, null, " NATURAL JOIN ");}
 // Renomeação (Relação)
 / _ "ρ" _ newName:Identifier _ "(" _ rel:Relation _ ")" _
   {return renameRelCol(newName, rel);}
 / _ "ρ" _ newName:Identifier _ "(" _ rel:UnionExceptIntersectRelation _ ")" _
   {return renameRelCol(newName, rel);}
 // Renomeação (Coluna)
 / _ "ρ" _ "[" _ cols:Columns _ "]" _ "(" _ rel:Relation _ ")" _
   {return renameRelCol(cols, rel, "column");}
 / _ "ρ" _ "[" _ cols:Columns _ "]" _ "(" _ rel:UnionExceptIntersectRelation _ ")" _
   {return renameRelCol(cols, rel, "column");}
 // Tabela
 / _ relation:Identifier _
   {return relation;}

// Tratamento do "E" e "OU" condicionais
ConditionStart
 = _ esq:Condition _ op:ConditionalAnd _ dir:ConditionStart _ {return esq + op + dir;}
 / _ esq:Condition _ op:ConditionalOr _ dir:ConditionStart _ {return esq + op + dir;}
 / cond:Condition {return cond;}

// Tratamento dos caracteres de comparação (<, >, =, ...)
Condition
 = _ esq:Type _ op:LesserEqual _ dir:Type {return esq + op + dir;}
 / _ esq:Type _ op:GreaterEqual _ dir:Type {return esq + op + dir;}
 / _ esq:Type _ op:Different _ dir:Type {return esq + op + dir;}
 / _ esq:Type _ op:GreaterThan _ dir:Type {return esq + op + dir;}
 / _ esq:Type _ op:LesserThan _ dir:Type {return esq + op + dir;}
 / _ esq:Type _ op:Equal _ dir:Type {return esq + op + dir;}
 / Type

//Tipos existentes
Type
 = "null"
 / "NULL"
 / "true"
 / "TRUE"
 / "false"
 / "FALSE"
 / SuperIdentifier
 / Float
 / Integer
 / String

ConditionalAnd '"E" condicional (^)'
 = "^" {return " AND ";}
 
ConditionalOr '"OU" condicional'
 = "v" {return " OR ";}

Union "Símbolo de união (∪)"
 = "∪" {return " UNION ";}
 
Intersect "Símbolo de intersecção (∩)"
 = "∩" {return "INTERSECT";}
 
Except "Símbolo de diferença (-)"
 = "-" {return "EXCEPT";}

JoinSymbol "Símbolo de junção (⨝)"
 = "⨝" {return " INNER JOIN ";}
 
DivisionSymbol "Símbolo de divisão (÷)"
 = "÷" {return createDivision();}
 
CrossJoinSymbol "Símbolo de Produto cartesiano"
 = "X" {return ",";}

LesserEqual "Menor ou igual (<=)"
 = "<=" {return " <= ";}
 
GreaterEqual "Maior ou igual (>=)"
 = ">=" {return " >= ";}
 
Different "Diferente (<>)"
 = "<>" {return " <> ";}
 
GreaterThan "Maior que (>)"
 = ">" {return " > ";}
 
LesserThan "Menor que (<)"
 = "<" {return " < ";}
 
Equal "Igual (=)"
 = "=" {return " = ";}

Columns "Colunas"
 = _ id:IdentifierExtended _ "," _ rest:Columns {return id + "," + rest; }
 / _ id:IdentifierExtended _ {return id;}

//Representa uma expressão do tipo "nomedatabela.campo"
SuperIdentifier "Identificador"
 = _ table:Identifier _ "." _ id:Identifier _ {return table + "." + id;}
 / _ id:Identifier _ {return id;}

// Representa uma palavra que possa ser entendida como nome de tabela, nome de campo...
Identifier "Identificador"
 = start:[a-zA-Z_]end:[a-zA-Z0-9_$]+ {return "`" + start + end.join("") + "`";}
 / char:[a-zA-Z_] {return "`" + char + "`";}

IdentifierVar "Variável"
 = start:[a-zA-Z_]end:[a-zA-Z0-9_$]+ {return start + end.join("");}

// Operações matemáticas
IdentifierExtended "Identificador ou operação matemática"
 = _ id:SuperIdentifier _ op:[+\-*/] _ num:Number _ {return id + " " + op + " " + num;}
 / _ id:SuperIdentifier _ {return id;}

Number "Float ou Inteiro"
 = Float
 / Integer

Float "Float"
  = [0-9.]+ { return parseFloat(text(), 10);}

Integer "Inteiro"
  = [0-9]+ { return parseInt(text(), 10);}

String "String"
  = '"' text:TextWithoutString* '"' {return "'" + text.join("") + "'";}

// Aspas duplas
TextWithoutString
 = !'"'char:.{return char;}

_ "Espaço em branco"
  = [ \t\n\r]*

__ "Espaço em branco"
  = [ \t\r]*

newline
  = [\n]*
