#! /usr/bin/python
# -*- coding: utf-8 -*-

from amara import binderytools
from Backoffice import *
import os
import shutil

xml = binderytools.bind_file('blueprint.xml')

# Move old apoidea to apoidea.old

# Create apoidea
backofficeDirectory = "~/Sites/engage/"
if os.path.exists(backofficeDirectory):
    shutil.rmtree(backofficeDirectory)

# Copy bones
shutil.copytree('bones', backofficeDirectory)

# Create sql
sql = """----------------------
-- Apoidea Database --
----------------------
------------------------------------------
-- mysql -u webuser -p < bd_apoidea.sql --
------------------------------------------

drop database apoidea2;
create database apoidea2;
use apoidea2;

"""

print 'SQL File'

for entity in xml.blueprint.entity:
    print "  Entity: " + entity.name.xml_text_content()
    #print "\n  Linhas: " + entity.rows.xml_text_content()

    sql += "create table " + entity.name.xml_text_content() + " (\n"

    primaryKey = ''
    keys = []
    for element in entity.element:
        #print "  Elemento: " + element.name.xml_text_content()
        row = [element.name.xml_text_content(), element.type.xml_text_content()]

        if hasattr(element, 'unsigned') and  element.unsigned.xml_text_content() == '1':
            row.append("unsigned")

        if hasattr(element, 'notNull') and element.notNull.xml_text_content() == '1':
            row.append("not null")

        if hasattr(element, 'unique') and element.unique.xml_text_content() == '1':
            row.append("unique")

        if hasattr(element, 'autoIncrement') and element.autoIncrement.xml_text_content() == '1':
            row.append("auto_increment")

        sql += "\t" + " ".join(row) + ",\n";

        if hasattr(element, "primaryKey") and element.primaryKey.xml_text_content() == '1':
            primaryKey = element.name.xml_text_content()

        if hasattr(element, "foreignKey"):
            keys.append(element.name.xml_text_content())

    if primaryKey:
        sql += "\tprimary key (" + primaryKey + ")"
    if keys:
        sql += ",\n"
        sql += "\tkey (" + ", ".join(keys) + ")"
    sql += "\n"
    sql += ");\n\n"

    if hasattr(entity, "insert"):
        for insert in entity.insert:
            values = []
            for value in insert.value:
                    values.append(value.xml_text_content())
            sql += 'insert into ' + entity.name.xml_text_content() + ' values (\'' + '\', \''.join(values) + '\');\n'
        sql += '\n'

sqlDirectory = backofficeDirectory + "sql/"
if not os.path.exists(sqlDirectory):
    os.makedirs(sqlDirectory)

file = open(sqlDirectory + 'bd_apoidea.sql', 'w')
file.writelines(sql)
file.close

print 'Backoffice'

# Create backoffice
backoffice = Backoffice(xml.blueprint)
backoffice.write(backofficeDirectory)
