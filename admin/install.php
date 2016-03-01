<?php

require_once "../config.php";

echo "Creating database...<br><br>\n\n";

$db = new SQLite3($dbfile);

if (!$db->exec("CREATE TABLE cw_projects
              (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
               active INTEGER DEFAULT 1,
               title TEXT,
               desc TEXT,
               summary TEXT,
               long_desc TEXT,
               specs TEXT,
               image TEXT,
               thumbnail TEXT,
               has_options BOOLEAN,
               proto_price REAL DEFAULT 0,
               proto_hash TEXT,
               proto_sold INTEGER DEFAULT 0,
               project_link TEXT,
               project_price REAL DEFAULT 0)"))
{
  echo $db->lastErrorMsg();
}
else
{
	echo "Table 'cw_projects' created successfully !<br>\n";
}

if (!$db->exec("CREATE TABLE cw_options
              (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
               option TEXT,
               desc TEXT)"))
{
  echo $db->lastErrorMsg();
}
else
{
	echo "Table 'cw_options' created successfully !<br>\n";
}

if (!$db->exec("CREATE TABLE cw_opts_values
              (option_id INTEGER NOT NULL,
               value_id INTEGER NOT NULL,
               name TEXT,
               cost REAL,
               title TEXT,
               PRIMARY KEY ('option_id', 'value_id'))"))
{
  echo $db->lastErrorMsg();
}
else
{
	echo "Table 'cw_opts_values' created successfully !<br>\n";
}

if (!$db->exec("CREATE TABLE cw_prj_opts
              (project_id INTEGER NOT NULL,
               option_id INTEGER NOT NULL,
               PRIMARY KEY ('project_id', 'option_id'))"))
{
  echo $db->lastErrorMsg();
}
else
{
	echo "Table 'cw_prj_opts' created successfully !<br>\n";
}

if (!$db->exec("CREATE TABLE cw_links
              (project_id INTEGER NOT NULL,
               options TEXT NOT NULL,
               hash TEXT,
               link TEXT,
               PRIMARY KEY ('project_id', 'options'))"))
{
  echo $db->lastErrorMsg();
}
else
{
	echo "Table 'cw_links' created successfully !<br>\n";
}

if (!$db->exec("CREATE TABLE cw_payments
              (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
               project_id INTEGER,
               amount REAL)"))
{
  echo $db->lastErrorMsg();
}
else
{
	echo "Table 'cw_payments' created successfully !<br>\n";
}

?>

