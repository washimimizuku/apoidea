#! /usr/bin/python
# -*- coding: utf-8 -*-

class Backoffice:
    document = ''
    submit = ''
    menu = ''

    def __init__(self, blueprint):
        """Initiates object Backoffice

        Sets entity to process

        returns nothing"""

        self.blueprint = blueprint

    def writeHeader(self, entity):
        """Write header of the backoffice file from the template header.tmpl

        Substitutes tag %%name%% with entity name

        returns the template after processing"""

        file = open('templates/header.tmpl', 'r')
        template = file.read()
        file.close

        template = template.replace('%%name%%', entity.name.xml_text_content())

        return template

    def writeList(self, entity):
        """Write Listing segment of the backoffice file from the template list.tmpl

        Substitutes tag %%name%% with entity name
        Substitutes tag %%title%% with entity title
        Substitutes tag %%primaryKey%% with entity primary key
        Substitutes tag %%pageSize%% with number of rows per page
        Substitutes tag %%listHeaderFields%% with Header Fields separated by ,
        Substitutes tag %%listSearchableFields%% with Searchable Fields with sql like syntax
        Substitutes tag %%listTableColumns%% with Header Fields enclosed in <td> tags
        Substitutes tag %%listTableRows%% with Header Rowss enclosed in <tr> tags
        Substitutes tag %%numColumns%% with number of columns in the table

        returns the template after processing"""

        file = open('templates/list.tmpl', 'r')
        template = file.read()
        file.close

        tempListHeaderFields = []
        tempListSearchableFields = []
        listTableColumns = ''
        listTableRows = ''
        numColumns = 3
        row = 1

        primaryKey = ''
        for element in entity.element:
            if hasattr(element, 'primaryKey') and element.primaryKey.xml_text_content() == '1':
                primaryKey = element.name.xml_text_content()

        tempListHeaderFields.append(primaryKey)

        for element in entity.element:
            if hasattr(element, 'header') and element.header.xml_text_content() == '1':
                # %%numColumns%%
                numColumns += 1
                # %%listHeaderFields%%
                tempListHeaderFields.append(element.name.xml_text_content())
                #print element.name.xml_text_content()

                # %%listTableColumns%% and %%listTableRows%%
                if hasattr(element, 'order') and element.order.xml_text_content() == '1':
                    listTableColumns += '                  <td>'+element.title.xml_text_content()+' <a href="'+element.name.xml_text_content()+'.php?order='+element.name.xml_text_content()+'&direction=asc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/up.png" /></a><a href="'+element.name.xml_text_content()+'.php?order='+element.name.xml_text_content()+'&direction=desc<?php if ($search) { echo "&search=$search";} ?>"><img class="small_button" src="images/down.png" /></a></td>'
                else:
                    listTableColumns += '                  <td>' + element.title.xml_text_content() +'</td>\n'

                if hasattr(element, 'foreignKey'):
                    for entity2 in self.blueprint.entity:
                        if entity2.name.xml_text_content() == element.foreignKey.xml_text_content():
                            id = ''
                            for element2 in entity2.element:
                                if hasattr(element2, 'primaryKey') and element2.primaryKey.xml_text_content() == '1':
                                    id = element2.name.xml_text_content()
                            name = entity2.name.xml_text_content()
                            description = entity2.descriptionElement.xml_text_content()

                            listTableRows += '<?php\n'
                            listTableRows += '      $query_' + name + ' = "select ' + description  + ' from ' + name + ' where ' + id + '=$row[' + str(row) + ']";\n'
                            listTableRows += '      $result_' + name + ' = mysql_query($query_' + name + ') or die("Invalid query: " . mysql_error());\n'
                            listTableRows += '      $' + name + '="[Nada]";\n'
                            listTableRows += '      if (mysql_num_rows($result_' + name + ') > 0) {\n'
                            listTableRows += '         $' + name + ' = "";\n'
                            listTableRows += '         while ($row_' + name + ' = mysql_fetch_row($result_' + name + ')) {\n'
                            listTableRows += '            $' + name + ' .= $row_' + name + '[0].", ";\n'
                            listTableRows += '         }\n'
                            listTableRows += '         $' + name + ' = substr($' + name + ',0,strlen($' + name + ')-2);\n'
                            listTableRows += '      }\n'
                            listTableRows += '?>\n'
                            listTableRows += '                  <td><?php echo($' + name + '); ?></td>\n'
                elif hasattr(element, 'type') and element.type.xml_text_content() == 'bool':
                    listTableRows += '                  <td><?php if ($row['+str(row)+'] == 1) { echo(\'Sim\'); } else { echo(\'Não\'); } ?></td>'
                else:
                    listTableRows += '                  <td><?php echo($row['+str(row)+']); ?></td>\n'
                #print listTableRows
                row += 1

            # %%listSearchableFields%%
            if hasattr(element, 'search') and element.search.xml_text_content() == '1':
                tempListSearchableFields.append(element.name.xml_text_content() + ' like \'%$search%\'')

        listHeaderFields = ', '.join(tempListHeaderFields)
        listSearchableFields = ' or '.join(tempListSearchableFields)

        template = template.replace('%%name%%', entity.name.xml_text_content())
        template = template.replace('%%title%%', entity.title.xml_text_content())
        template = template.replace('%%primaryKey%%', primaryKey)
        template = template.replace('%%pageSize%%', entity.rows.xml_text_content())
        template = template.replace('%%listHeaderFields%%', listHeaderFields)
        template = template.replace('%%listSearchableFields%%', listSearchableFields)
        template = template.replace('%%listTableColumns%%', listTableColumns)
        template = template.replace('%%listTableRows%%', listTableRows)
        template = template.replace('%%numColumns%%', str(numColumns))

        return template

    def writeCreate(self, entity):
        """Write Create segment of the backoffice file from the template create.tmpl

        Substitutes tag %%name%% with entity name
        Substitutes tag %%title%% with entity title
        Substitutes tag %%listCreateRows%% with input rows enclosed in <tr> tags

        returns the template after processing"""

        file = open('templates/create.tmpl', 'r')
        template = file.read()
        file.close

        primaryKey = ''
        for element in entity.element:
            if hasattr(element, 'primaryKey') and element.primaryKey.xml_text_content() == '1':
                primaryKey = element.name.xml_text_content()

        listCreateRows = ''
        for element in entity.element:
            if hasattr(element, 'hidden') and element.hidden.xml_text_content() == '1':
                pass
            elif element.name.xml_text_content() != primaryKey:
                if hasattr(element, 'foreignKey'):
                    for entity2 in self.blueprint.entity:
                        if entity2.name.xml_text_content() == element.foreignKey.xml_text_content():
                            id = ''
                            for element2 in entity2.element:
                                if hasattr(element2, 'primaryKey') and element2.primaryKey.xml_text_content() == '1':
                                    id = element2.name.xml_text_content()
                            name = entity2.name.xml_text_content()
                            description = entity2.descriptionElement.xml_text_content()

                            listCreateRows += '               <tr>\n'
                            listCreateRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                            listCreateRows += '                  <td class="dark">\n'
                            listCreateRows += '                     <select name="'+element.name.xml_text_content()+'">\n'
                            listCreateRows += '                        <option value="0">[Nada]</option>\n'
                            listCreateRows += '<?php\n'
                            listCreateRows += '      $query_' + name + ' = "select ' + id + ', ' + description + ' from ' + name + ' order by ' + id + '";\n'
                            listCreateRows += '      $result_' + name + ' = mysql_query($query_' + name + ') or die("Invalid query: " . mysql_error());\n'
                            listCreateRows += '      for ($i = 0; $i < mysql_num_rows($result_' + name + '); $i++) {\n'
                            listCreateRows += '         $row_' + name + ' = mysql_fetch_row($result_' + name + ') or die("Could not retrieve row: " . mysql_error());\n'
                            listCreateRows += '?>\n'
                            listCreateRows += '                        <option value="<?php echo($row_' + name + '[0]); ?>"><?php echo($row_' + name + '[1]); ?></option>\n'
                            listCreateRows += '<?php\n'
                            listCreateRows += '      }\n'
                            listCreateRows += '?>\n'
                            listCreateRows += '                     </select>\n'
                            listCreateRows += '                  </td>\n'
                            listCreateRows += '               </tr>\n'
                elif hasattr(element, 'type') and element.type.xml_text_content() == 'timestamp':
                    listCreateRows += '               <tr>\n'
                    listCreateRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listCreateRows += '                  <td class="dark">\n'
                    listCreateRows += '<?php\n'
                    listCreateRows += '      setlocale(LC_ALL, "pt_PT@euro");\n'
                    listCreateRows += '      $date = getdate();\n'
                    listCreateRows += '      $year = $date[\'year\'];\n'
                    listCreateRows += '      $month_text = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");\n'
                    listCreateRows += '?>\n'
                    listCreateRows += '                     <select name="'+element.name.xml_text_content()+'Year">\n'
                    listCreateRows += '                        <option value="0">Ano</option>\n'
                    listCreateRows += '<?php\n'
                    listCreateRows += '      for ($i = $year -2; $i < $year + 3; $i++) {\n'
                    listCreateRows += '?>\n'
                    listCreateRows += '                        <option value="<?php echo($i) ?>"<?php if ($i == $year) { echo(\' selected\'); } ?>><?php echo($i) ?></option>\n'
                    listCreateRows += '<?php\n'
                    listCreateRows += '      }\n'
                    listCreateRows += '?>\n'
                    listCreateRows += '                     </select>\n'
                    listCreateRows += '                     <select name="'+element.name.xml_text_content()+'Month">\n'
                    listCreateRows += '                        <option value="0">Mês</option>\n'
                    listCreateRows += '<?php\n'
                    listCreateRows += '      for ($i = 1; $i < 13; $i++) {\n'
                    listCreateRows += '?>\n'
                    listCreateRows += '                        <option value="<?php echo($i) ?>"<?php if ($i == $date[\'mon\']) { echo(\' selected\'); } ?>><?php echo($month_text[$i-1]) ?></option>\n'
                    listCreateRows += '<?php\n'
                    listCreateRows += '      }\n'
                    listCreateRows += '?>\n'
                    listCreateRows += '                     </select>\n'
                    listCreateRows += '                     <select name="'+element.name.xml_text_content()+'Day">\n'
                    listCreateRows += '                        <option value="0">Dia</option>\n'
                    listCreateRows += '<?php\n'
                    listCreateRows += '      for ($i = 1; $i < 32; $i++) {\n'
                    listCreateRows += '?>\n'
                    listCreateRows += '                        <option value="<?php echo($i) ?>"<?php if ($i == $date[\'mday\']) { echo(\' selected\'); } ?>><?php echo($i) ?></option>\n'
                    listCreateRows += '<?php\n'
                    listCreateRows += '      }\n'
                    listCreateRows += '?>\n'
                    listCreateRows += '                     </select>\n'
                    listCreateRows += '                  </td>\n'
                    listCreateRows += '               </tr>\n'
                elif hasattr(element, 'type') and element.type.xml_text_content() == 'bool':
                    listCreateRows += '               <tr>\n'
                    listCreateRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listCreateRows += '                  <td class="dark">\n'
                    listCreateRows += '                     <input type="radio" name="'+element.name.xml_text_content()+'" value="1" checked /> Sim <input type="radio" name="'+element.name.xml_text_content()+'" value="0" /> Não\n'
                    listCreateRows += '                  </td>\n'
                    listCreateRows += '               </tr>\n'
                elif hasattr(element, 'password') and element.password.xml_text_content() == '1':
                    listCreateRows += '               <tr>\n'
                    listCreateRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listCreateRows += '                  <td class="dark">\n'
                    listCreateRows += '                     <input type="password" size="40" name="'+element.name.xml_text_content()+'" />\n'
                    listCreateRows += '                  </td>\n'
                    listCreateRows += '               </tr>\n'

                    listCreateRows += '               <tr>\n'
                    listCreateRows += '                  <td class="title">'+element.title.xml_text_content()+' (confirmar)</td>\n'
                    listCreateRows += '                  <td class="dark">\n'
                    listCreateRows += '                     <input type="password" size="40" name="'+element.name.xml_text_content()+'_check" />\n'
                    listCreateRows += '                  </td>\n'
                    listCreateRows += '               </tr>\n'
                else:
                    listCreateRows += '               <tr>\n'
                    listCreateRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listCreateRows += '                  <td class="dark">\n'
                    if hasattr(element, 'textarea') and element.textarea.xml_text_content() == '1':
                        listCreateRows += '                     <textarea name="'+element.name.xml_text_content()+'"></textarea>\n'
                    else:
                        listCreateRows += '                     <input type="text" size="40" name="'+element.name.xml_text_content()+'" />\n'
                    listCreateRows += '                  </td>\n'
                    listCreateRows += '               </tr>\n'

        template = template.replace('%%name%%', entity.name.xml_text_content())
        template = template.replace('%%title%%', entity.title.xml_text_content())
        template = template.replace('%%listCreateRows%%', listCreateRows)

        return template

    def writeModify(self, entity):
        """Write Modify segment of the backoffice file from the template modify.tmpl

        Substitutes tag %%name%% with entity name
        Substitutes tag %%title%% with entity title
        Substitutes tag %%primaryKey%% with entity primary key
        Substitutes tag %%listFields%% with Fields in sql like syntax
        Substitutes tag %%listModifyRows%% with input rows enclosed in <tr> tags

        returns the template after processing"""

        file = open('templates/modify.tmpl', 'r')
        template = file.read()
        file.close

        primaryKey = ''
        for element in entity.element:
            if hasattr(element, 'primaryKey') and element.primaryKey.xml_text_content() == '1':
                primaryKey = element.name.xml_text_content()

        listFields = []
        listModifyRows = ''
        index = 0
        for element in entity.element:
            if hasattr(element, 'hidden') and element.hidden.xml_text_content() == '1':
                pass
            elif element.name.xml_text_content() != primaryKey:
                if hasattr(element, 'foreignKey'):
                    for entity2 in self.blueprint.entity:
                        if entity2.name.xml_text_content() == element.foreignKey.xml_text_content():
                            id = ''
                            for element2 in entity2.element:
                                if hasattr(element2, 'primaryKey') and element2.primaryKey.xml_text_content() == '1':
                                    id = element2.name.xml_text_content()
                            name = entity2.name.xml_text_content()
                            description = entity2.descriptionElement.xml_text_content()

                            listModifyRows += '               <tr>\n'
                            listModifyRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                            listModifyRows += '                  <td class="dark">\n'
                            listModifyRows += '                     <select name="'+element.name.xml_text_content()+'">\n'
                            listModifyRows += '                        <option value="0">[Nada]</option>\n'
                            listModifyRows += '<?php\n'
                            listModifyRows += '      $query_' + name + ' = "select ' + id + ', ' + description + ' from ' + name + ' order by ' + id + '";\n'
                            listModifyRows += '      $result_' + name + ' = mysql_query($query_' + name + ') or die("Invalid query: " . mysql_error());\n'
                            listModifyRows += '      $selected = "";\n'
                            listModifyRows += '      for ($i = 0; $i < mysql_num_rows($result_' + name + '); $i++) {\n'
                            listModifyRows += '         $row_' + name + ' = mysql_fetch_row($result_' + name + ') or die("Could not retrieve row: " . mysql_error());\n'
                            listModifyRows += '         if ($row_' + name + '[0] == $row['+str(index)+']) {\n'
                            listModifyRows += '            $selected = " selected";\n'
                            listModifyRows += '         } else {\n'
                            listModifyRows += '            $selected = "";\n'
                            listModifyRows += '         }\n'
                            listModifyRows += '?>\n'
                            listModifyRows += '                        <option value="<?php echo($row_' + name + '[0]); ?>"<?php echo($selected); ?>><?php echo($row_' + name + '[1]); ?></option>\n'
                            listModifyRows += '<?php\n'
                            listModifyRows += '      }\n'
                            listModifyRows += '?>\n'
                            listModifyRows += '                     </select>\n'
                            listModifyRows += '                  </td>\n'
                            listModifyRows += '               </tr>\n'
                elif hasattr(element, 'type') and element.type.xml_text_content() == 'timestamp':
                    listModifyRows += '               <tr>\n'
                    listModifyRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listModifyRows += '                  <td class="dark">\n'
                    listModifyRows += '<?php\n'
                    listModifyRows += '      setlocale(LC_ALL, "pt_PT@euro");\n'
                    listModifyRows += '      $date = getdate();\n'
                    listModifyRows += '      $year = $date[\'year\'];\n'
                    listModifyRows += '      $month_text = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");\n'
                    listModifyRows += '      $'+element.name.xml_text_content()+'Year = substr($row['+str(index)+'],0,4);\n'
                    listModifyRows += '      $'+element.name.xml_text_content()+'Month = substr($row['+str(index)+'],5,2);\n'
                    listModifyRows += '      $'+element.name.xml_text_content()+'Day = substr($row['+str(index)+'],8,2);\n'
                    listModifyRows += '?>\n'
                    listModifyRows += '                     <select name="'+element.name.xml_text_content()+'Year">\n'
                    listModifyRows += '                        <option value="0">Ano</option>\n'
                    listModifyRows += '<?php\n'
                    listModifyRows += '      for ($i = $year -2; $i < $year + 3; $i++) {\n'
                    listModifyRows += '?>\n'
                    listModifyRows += '                        <option value="<?php echo($i) ?>"<?php if ($i == $'+element.name.xml_text_content()+'Year) { echo(\' selected\'); } ?>><?php echo($i) ?></option>\n'
                    listModifyRows += '<?php\n'
                    listModifyRows += '      }\n'
                    listModifyRows += '?>\n'
                    listModifyRows += '                     </select>\n'
                    listModifyRows += '                     <select name="'+element.name.xml_text_content()+'Month">\n'
                    listModifyRows += '                        <option value="0">Mês</option>\n'
                    listModifyRows += '<?php\n'
                    listModifyRows += '      for ($i = 1; $i < 13; $i++) {\n'
                    listModifyRows += '?>\n'
                    listModifyRows += '                        <option value="<?php echo($i) ?>"<?php if ($i == $'+element.name.xml_text_content()+'Month) { echo(\' selected\'); } ?>><?php echo($month_text[$i-1]) ?></option>\n'
                    listModifyRows += '<?php\n'
                    listModifyRows += '      }\n'
                    listModifyRows += '?>\n'
                    listModifyRows += '                     </select>\n'
                    listModifyRows += '                     <select name="'+element.name.xml_text_content()+'Day">\n'
                    listModifyRows += '                        <option value="0">Dia</option>\n'
                    listModifyRows += '<?php\n'
                    listModifyRows += '      for ($i = 1; $i < 32; $i++) {\n'
                    listModifyRows += '?>\n'
                    listModifyRows += '                        <option value="<?php echo($i) ?>"<?php if ($i == $'+element.name.xml_text_content()+'Day) { echo(\' selected\'); } ?>><?php echo($i) ?></option>\n'
                    listModifyRows += '<?php\n'
                    listModifyRows += '      }\n'
                    listModifyRows += '?>\n'
                    listModifyRows += '                     </select>\n'
                    listModifyRows += '                  </td>\n'
                    listModifyRows += '               </tr>\n'
                elif hasattr(element, 'type') and element.type.xml_text_content() == 'bool':
                    listModifyRows += '               <tr>\n'
                    listModifyRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listModifyRows += '                  <td class="dark">\n'
                    listModifyRows += '                     <input type="radio" name="'+element.name.xml_text_content()+'" value="1"<?php if ($row['+str(index)+'] == 1) { echo(\' checked\'); } ?> /> Sim <input type="radio" name="'+element.name.xml_text_content()+'" value="0"<?php if ($row['+str(index)+'] == 0) { echo(\' checked\'); } ?> /> Não\n'
                    listModifyRows += '                  </td>\n'
                    listModifyRows += '               </tr>\n'
                elif hasattr(element, 'password') and element.password.xml_text_content() == '1':
                    listModifyRows += '               <tr>\n'
                    listModifyRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listModifyRows += '                  <td class="dark">\n'
                    listModifyRows += '                     <input type="password" size="40" name="'+element.name.xml_text_content()+'" />\n'
                    listModifyRows += '                  </td>\n'
                    listModifyRows += '               </tr>\n'

                    listModifyRows += '               <tr>\n'
                    listModifyRows += '                  <td class="title">'+element.title.xml_text_content()+' (confirmar)</td>\n'
                    listModifyRows += '                  <td class="dark">\n'
                    listModifyRows += '                     <input type="password" size="40" name="'+element.name.xml_text_content()+'_check" />\n'
                    listModifyRows += '                  </td>\n'
                    listModifyRows += '               </tr>\n'
                else:
                    listModifyRows += '               <tr>\n'
                    listModifyRows += '                  <td class="title">'+element.title.xml_text_content()+'</td>\n'
                    listModifyRows += '                  <td class="dark">\n'
                    if hasattr(element, 'textarea') and element.textarea.xml_text_content() == '1':
                        listModifyRows += '                     <textarea name="'+element.name.xml_text_content()+'"><?php echo($row['+str(index)+']); ?></textarea>\n'
                    else:
                        listModifyRows += '                     <input type="text" size="40" name="'+element.name.xml_text_content()+'" value="<?php echo($row['+str(index)+']); ?>" />\n'
                    listModifyRows += '                  </td>\n'
                    listModifyRows += '               </tr>\n'
                index += 1
                listFields.append(element.name.xml_text_content())

        template = template.replace('%%name%%', entity.name.xml_text_content())
        template = template.replace('%%title%%', entity.title.xml_text_content())
        template = template.replace('%%primaryKey%%', primaryKey)
        template = template.replace('%%listFields%%', ', '.join(listFields))
        template = template.replace('%%listModifyRows%%', listModifyRows)

        return template

    def writeErase(self, entity):
        """Write Erase segment of the backoffice file from the template erase.tmpl

        Substitutes tag %%name%% with entity name
        Substitutes tag %%title%% with entity title
        Substitutes tag %%primaryKey%% with entity primary key
        Substitutes tag %%listFields%% with Fields in sql like syntax
        Substitutes tag %%listTableColumns%% with element names enclosed in <td> tags
        Substitutes tag %%listTableRows%% with element values enclosed in <td> tags
        Substitutes tag %%numColumns%% with number of visible elements

        returns the template after processing"""

        file = open('templates/erase.tmpl', 'r')
        template = file.read()
        file.close

        primaryKey = ''
        for element in entity.element:
            if hasattr(element, 'primaryKey') and element.primaryKey.xml_text_content() == '1':
                primaryKey = element.name.xml_text_content()

        listFields = []
        listTableColumns = ''
        listTableRows = ''
        index = 0
        for element in entity.element:
            if hasattr(element, 'hidden') and element.hidden.xml_text_content() == '1':
                pass
            elif element.name.xml_text_content() != primaryKey:
                if hasattr(element, 'foreignKey'):
                    for entity2 in self.blueprint.entity:
                        if entity2.name.xml_text_content() == element.foreignKey.xml_text_content():
                            id = ''
                            for element2 in entity2.element:
                                if hasattr(element2, 'primaryKey') and element2.primaryKey.xml_text_content() == '1':
                                    id = element2.name.xml_text_content()
                            name = entity2.name.xml_text_content()
                            description = entity2.descriptionElement.xml_text_content()

                            listTableRows += '<?php\n'
                            listTableRows += '         $query_' + name + ' = "select ' + description + ' from ' + name + ' where ' + id + '=$row['+str(index)+']";\n'
                            listTableRows += '         $result_' + name + ' = mysql_query($query_' + name + ') or die("Invalid query: " . mysql_error());\n'
                            listTableRows += '         $row_' + name + ' = mysql_fetch_row($result_' + name + ') or die("Could not retrieve row: " . mysql_error());\n'
                            listTableRows += '         $' + name + ' = $row_' + name + '[0];\n'
                            listTableRows += '?>\n'
                            listTableRows += '                  <td><?php echo($' + name + '); ?></td>\n'
                elif hasattr(element, 'type') and element.type.xml_text_content() == 'bool':
                    listTableRows += '                  <td><?php if ($row['+str(index)+'] == 1) { echo(\'Sim\'); } else { echo(\'Não\');} ?></td>\n'
                else:
                    listTableRows += '                  <td><?php echo($row['+str(index)+']); ?></td>\n'

                listTableColumns += '                  <td>' + element.title.xml_text_content() + '</td>\n'
                index += 1
                listFields.append(element.name.xml_text_content())

        template = template.replace('%%name%%', entity.name.xml_text_content())
        template = template.replace('%%title%%', entity.title.xml_text_content())
        template = template.replace('%%primaryKey%%', primaryKey)
        template = template.replace('%%listFields%%', ', '.join(listFields))
        template = template.replace('%%listTableColumns%%', listTableColumns)
        template = template.replace('%%listTableRows%%', listTableRows)
        template = template.replace('%%numColumns%%', str(index + 3))

        return template

    def writeFooter(self, entity):
        """Write footer of the backoffice file from the template footer.tmpl

        Just reads the file. Nothing to substitute

        returns the template after processing"""

        file = open('templates/footer.tmpl', 'r')
        template = file.read()
        file.close

        return template

    def writeSubmit(self, entity):
        """Write submit backoffice file from the template submit.tmpl

        returns the template after processing"""

        file = open('templates/submit.tmpl', 'r')
        template = file.read()
        file.close

        listPost = ''
        listVerification = ''
        listFields = []
        listValues = []
        listUpdateFields = []
        for element in entity.element:
            if hasattr(element, 'hidden') and element.hidden.xml_text_content() == '1':
                pass
            elif hasattr(element, 'primaryKey') and element.primaryKey.xml_text_content() == '1':
                pass
            else:
                if hasattr(element, 'type') and element.type.xml_text_content() == 'timestamp':
                    listPost += '   $' + element.name.xml_text_content() + ' = $_POST[\'' + element.name.xml_text_content() + 'Year\']."-".$_POST[\'' + element.name.xml_text_content() + 'Month\']."-".$_POST[\'' + element.name.xml_text_content() + 'Day\'];\n'
                elif hasattr(element, 'password') and element.password.xml_text_content() == '1':
                    listPost += '   $' + element.name.xml_text_content() + '_temp = $_POST[\'' + element.name.xml_text_content() + '\'];\n'
                    listPost += '   $' + element.name.xml_text_content() + '_check = $_POST[\'' + element.name.xml_text_content() + '_check\'];\n'
                    listPost += '   $' + element.name.xml_text_content() + ' = md5($' + element.name.xml_text_content() + '_temp);\n'

                    listVerification += '   if (($' + element.name.xml_text_content() + '_temp != $' + element.name.xml_text_content() + '_check) && ($action != "erase2")) {\n'
                    listVerification += '      $msg .= "Os campos de ' + element.title.xml_text_content() + ' têm de ser iguais.<br>";\n'
                    listVerification += '   }\n'
                else:
                    listPost += '   $' + element.name.xml_text_content() + ' = $_POST[\'' + element.name.xml_text_content() + '\'];\n'
                if hasattr(element, 'notNull') and element.notNull.xml_text_content() == '1':
                    listVerification += '   if (!($' + element.name.xml_text_content() + ') && ($action != "erase2")) {\n'
                    listVerification += '      $msg .= "O campo ' + element.title.xml_text_content() + ' é obrigatório.<br>";\n'
                    listVerification += '   }\n'
                listFields.append(element.name.xml_text_content())
                listValues.append("'$" + element.name.xml_text_content() + "'")
                listUpdateFields.append(element.name.xml_text_content() + "='$" + element.name.xml_text_content() + "'")

        primaryKey = ''
        for element in entity.element:
            if hasattr(element, 'primaryKey') and element.primaryKey.xml_text_content() == '1':
                primaryKey = element.name.xml_text_content()

        template = template.replace('%%post%%', listPost)
        template = template.replace('%%verification%%', listVerification)
        template = template.replace('%%name%%', entity.name.xml_text_content())
        template = template.replace('%%primaryKey%%', primaryKey)
        template = template.replace('%%listFields%%', ', '.join(listFields))
        template = template.replace('%%listValues%%', ', '.join(listValues))
        template = template.replace('%%listUpdateFields%%', ', '.join(listUpdateFields))

        return template

    def writeMenu(self, blueprint):
        """Write menu file from the template menu.tmpl

        returns the template after processing"""

        file = open('templates/menu.tmpl', 'r')
        template = file.read()
        file.close

        menuCode = ''
        for menu in blueprint.menu:
            menuCode += '         <li><a href="#" title="' + menu.name.xml_text_content() + '">' + menu.name.xml_text_content() + '</a>\n'
            menuCode += '            <ul>\n'
            classTop = ' class="top"'
            for entity in menu.entity:
                for entity2 in blueprint.entity:
                    if entity.xml_text_content() == entity2.name.xml_text_content():
                        menuCode += '               <li' + classTop + '><a href="' + entity2.name.xml_text_content() + '.php" title="' + entity2.title.xml_text_content() + '">' + entity2.title.xml_text_content() + '</a></li>\n'
                        classTop = ''
            menuCode += '            </ul>\n'
            menuCode += '         </li>\n'

        template = template.replace('%%menu%%', menuCode)

        return template

    def write(self, backofficeDirectory):
        """Write backoffice file from output by other functions

        returns the processed document"""

        for entity in self.blueprint.entity:
            #print "  Entity: " + entity.name.xml_text_content()

            print '  ' + entity.name.xml_text_content() + '.php'
            header = self.writeHeader(entity)
            list   = self.writeList(entity)
            create = self.writeCreate(entity)
            modify = self.writeModify(entity)
            erase  = self.writeErase(entity)
            footer = self.writeFooter(entity)

            self.document = ''.join((header, list, create, modify, erase, footer))

            file = open(backofficeDirectory + entity.name.xml_text_content()+'.php', 'w')
            file.write(self.document)
            file.close

            print '  ' + entity.name.xml_text_content() + '_submit.php'
            self.submit = self.writeSubmit(entity)

            file = open(backofficeDirectory+entity.name.xml_text_content()+'_submit.php', 'w')
            file.write(self.submit)
            file.close

        print '  menu.php'
        self.menu = self.writeMenu(self.blueprint)

        file = open(backofficeDirectory + 'menu.php', 'w')
        file.write(self.menu)
        file.close

        return self
