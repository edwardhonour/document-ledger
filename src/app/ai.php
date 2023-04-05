<?php

ini_set('display_errors',1);
error_reporting(E_ERROR | E_PARSE);
ini_set('display_startup_errors',1);
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
require_once('class.PSDB.php');

class SQLLabsAI {

        protected $dbh;
        protected $db;

		protected $table_name;
		protected $base_table;
		protected $cols;
		protected $form_selector;
		protected $table_selector;
		protected $panel_selector;
		protected $dashboard_selector;

		protected $form_directory;
		protected $table_directory;
		protected $panel_directory;
		protected $dashboard_directory;

		protected $form_class_name;
		protected $table_class_name;
		protected $panel_class_name;
		protected $dashboard_class_name;

		protected $form_ts_file;
		protected $form_template_file;
		protected $form_css_file;

		protected $table_ts_file;
		protected $table_template_file;
		protected $table_css_file;

		protected $panel_file;
		protected $dashboard_file;

		protected $base_dir;
		protected $post;

		function __construct() {
			$this->post=array();
			$this->post['files']=array();
			$this->base_dir="/home/protective/public_html/api/files";
		}

	    function send_zip() {
            $zip = new ZipArchive(); 
            $zip_name = time().".zip"; 
            if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE) { 
                    die("* Sorry ZIP creation failed.");
            }
            foreach($this->post['files'] as $file) { 
                $zip->addFile($file); 
            }
            $zip->close();
            if(file_exists($zip_name)) {
                header('Content-type: application/zip');
                header('Content-Disposition: attachment; filename="'.$zip_name.'"');
                readfile($zip_name);
                unlink($zip_name);
            }
		}

		function is_json($data) {
    		if (!empty($data)) {
        	    return is_string($data) && 
          	    is_array(json_decode($data, true)) ? true : false;
    		}
    		return false;
		}

		function get_cols($table) {
			$X=new PSDB();
            $this->cols=$X->get_cols($table);
		}

        function make_class_name($table, $type) {

			$array=explode("-",$table);
			$class_name="";
			foreach($array as $a) {
    			 $class_name .= ucfirst($a);
			}
			$class_name=$class_name . ucfirst($type);
			$class_name.="Component";
			return $class_name;

		}

		function print_sql_input($file, $col, $dstyle, $dlabel) { 
			fwrite($file,"    <sql-input");
			fwrite($file," [col]=\"'" . $col['Field'] . "'\"");
			fwrite($file," [style]=\"'" . $dstyle . "'\"");
			fwrite($file," [label]=\"'" . $dlabel . "'\">\r\n");
			fwrite($file,"    </sql-input>\r\n\r\n");
		} 

		function print_sql_select($file, $col, $dstyle, $dlabel, $dsql) { 
			fwrite($file,"   <sql-data-select");
			fwrite($file," [col]=\"'" . $col['Field'] . "'\"");
			fwrite($file," [style]=\"'" . $dstyle . "'\"");
			fwrite($file," [label]=\"'" . $dlabel . "'\"");
			fwrite($file," [sql]=\"'" . $dsql . "'\">\r\n");
			fwrite($file,"   </sql-data-select>\r\n \r\n");
		}	 

		function print_sql_radio($file, $col, $dstyle, $dlabel, $dsql) { 
			fwrite($file, "   <sql-radio-group");
			fwrite($file, " [col]=\"'" . $col['Field'] . "'\"");
			fwrite($file, " [style]=\"'" . $dstyle . "'\"");
			fwrite($file, " [label]=\"'" . $dlabel . "'\"\r\n");
			fwrite($file, "      [sql]=\"'" . $dsql . "'\">\r\n");
			fwrite($file, "   </sql-radio-group>\r\n \r\n");
		} 

		function print_sql_datepicker($file, $col, $dstyle, $dlabel) { 
			fwrite($file,"   <sql-datepicker");
			fwrite($file," [col]=\"'" . $col['Field'] . "'\"");
			fwrite($file," [style]=\"'" . $dstyle . "'\"");
			fwrite($file," [label]=\"'" . $dlabel . "'\">\r\n");
			fwrite($file,"    </sql-datepicker>\r\n \r\n");
		}	 

		function print_sql_checkbox($file, $col, $dstyle) { 
			fwrite($file,"  <sql-checkbox ");
			fwrite($file,"     [col]=\"'" . $col['Field'] . "'\"");
			fwrite($file,"     [style]=\"'" . $dstyle . "'\"");
			fwrite($file,"     [label]=\"'" . $dlabel . "'\"");
			fwrite($file,"  </sql-checkbox>\r\n \r\n");
		} 

		function print_sql_textarea($file, $col, $dstyle) { 
			fwrite($file,"  <sql-textarea ");
			fwrite($file,"     [col]=\"'" . $col['Field'] ."'\"");
			fwrite($file,"     [style]=\"'" . $dstyle . "'\"");
			fwrite($file,"     [label]=\"'" . $dlabel . "'\"");
			fwrite($file,"  </sql-textarea>\r\n \r\n");
		} 

		function print_sql_editor($file, $col, $dstyle) { 
			fwrite($file,"  <sql-editor ");
			fwrite($file,"     [col]=\"'" . $col['Field'] ."'\"");
			fwrite($file,"     [style]=\"'" . $dstyle . "'\"");
			fwrite($file,"     [label]=\"'" . $dlabel . "'\"></sql-editor>\r\n");
		} 

		function create_form_module_min() {
			fwrite($this->form_ts_file,"import { Component } from '@angular/core';\r\n");
			fwrite($this->form_ts_file,"import { CommonModule } from '@angular/common';\r\n");
			fwrite($this->form_ts_file,"import { SqlComponentsModule, SqlDataSelectComponent, SqlDeleteComponent, SqlSubmitComponent } from 'sql-components';\r\n");
			fwrite($this->form_ts_file,"import {MatDialogModule} from '@angular/material/dialog';\r\n");
			fwrite($this->form_ts_file,"\r\n");
			fwrite($this->form_ts_file,"\r\n");
			fwrite($this->form_ts_file,"@Component({\r\n");
			fwrite($this->form_ts_file,"  selector: '" . $this->form_selector . "',\r\n");
			fwrite($this->form_ts_file,"  standalone: true,\r\n");
			fwrite($this->form_ts_file,"  imports: [CommonModule, SqlComponentsModule, SqlSubmitComponent, SqlDataSelectComponent, SqlDeleteComponent, MatDialogModule], \r\n");
			fwrite($this->form_ts_file,"  templateUrl: './" . $this->form_directory . ".component.html',\r\n"); 
			fwrite($this->form_ts_file,"  styleUrls: ['./" . $this->form_directory . ".component.css']\r\n");
			fwrite($this->form_ts_file," })\r\n");
			fwrite($this->form_ts_file," export class " . $this->form_class_name . " {\r\n");
			fwrite($this->form_ts_file," \r\n");
			fwrite($this->form_ts_file,"}\r\n");
			fwrite($this->form_ts_file,"\r\n");
			fwrite($this->form_ts_file,"\r\n");	   
			fclose($this->form_ts_file);
		}

		function create_form_template_vert3_min() {
	
			$ccount=0;
			$count=1;
			foreach($this->cols as $c) if ($c['Field']!="id" && $c['Field']!="create_timestamp") $count++;   

			$percount=round($count/3,0);
			if ($percount==0) $percount=1;
			fwrite($this->form_template_file,"<sql-form \r\n");
			fwrite($this->form_template_file," [table]=\"'" . $this->table_name . "'\" [embedded]=\"'Y'\"\r\n");
			fwrite($this->form_template_file," [title]=\"'" . $this->table_name . "'\"\r\n");
			fwrite($this->form_template_file," [id]=\"'0'\" [id2]=\"''\" [id3]=\"''\"\r\n"); 
			fwrite($this->form_template_file," [default_col]= \"''\" [default_value]=\"''\">\r\n");
			fwrite($this->form_template_file,"\r\n  \r\n");
			fwrite($this->form_template_file,"<div class=\"row\">\r\n");

			fwrite($this->form_template_file,"<div class=\"col-sm-12 col-lg-6 col-xl-4\">\r\n");

			$ccount++;
			$i=1;
			foreach ($this->cols as $col) {
				if ($col['Field']!='id'&&$col['Field']!='create_timestamp') {
	  				$dtype="";
   			 		$dsql="select id, name from sometable order by 2";
			    	$dtype="hidden";
     				$dlabel=$col['Field'];
     				$dstyle="width:400px;";

	     			if (strpos($col['Type'],"varchar")!==false) $dtype="input";
    	 			if (strpos($col['Type'],"int")!==false) $dtype="select";
     				if (strpos($col['Type'],"date")!==false) $dtype="datepicker";
    
				    $c=$col['Comment'];
     				if ($this->is_json($c)) {
     	  				$json=json_decode($c,true);
          				if (isset($json['form_type'])) $dtype=$json['form_type'];
          				if (isset($json['form_style'])) $dstyle=$json['form_style'];
                    	if (isset($json['form'])) $dlabel=$json['form']; 
			    	 }
 
		     	if ($dtype=='input') $this->print_sql_input($this->form_template_file,$col, $dstyle, $dlabel);
 		     	if ($dtype=='select') $this->print_sql_select($this->form_template_file,$col, $dstyle, $dlabel, $dsql);
    		 	if ($dtype=='radio') $this->print_sql_radio($this->form_template_file,$col, $dstyle, $dlabel, $dsql);
		     	if ($dtype=='datepicker') $this->print_sql_datepicker($this->form_template_file,$col, $dstyle, $dlabel);
  			 	if ($dtype=='checkbox') $this->print_sql_checkbox($this->form_template_file,$col, $dstyle, $dlabel, $dsql);
  		     	if ($dtype=='textarea') $this->print_sql_textarea($this->form_template_file,$col, $dstyle, $dlabel);
		     	if ($dtype=='editor') $this->print_sql_editor($this->form_template_file,$col, $dstyle, $dlabel);
     
		     	$i++;
     		 	if ($i>$percount) {     
         			$i=1; 
         			fwrite($this->form_template_file,"</div>\r\n");   
         			fwrite($this->form_template_file,"<div class=\"col-sm-12 col-lg-6 col-xl-4\">\r\n");  
					$ccount++;
     			}
			   }
			}
			if ($ccount==1) {
				fwrite($this->form_template_file,"</div>\r\n");   
				fwrite($this->form_template_file,"<div class=\"col-sm-12 col-lg-6 col-xl-4\">\r\n");  
				fwrite($this->form_template_file,"</div>\r\n");   
				fwrite($this->form_template_file,"</div>\r\n");   
				fwrite($this->form_template_file,"<div class=\"col-sm-12 col-lg-6 col-xl-4\">\r\n");  
			}
			if ($ccount==2) {
				fwrite($this->form_template_file,"</div>\r\n");   
				fwrite($this->form_template_file,"<div class=\"col-sm-12 col-lg-6 col-xl-4\">\r\n");  
			}

			fwrite($this->form_template_file,"<div class=\"row\">\r\n");
			fwrite($this->form_template_file,"<div class=\"col-sm-12\">\r\n");  
			fwrite($this->form_template_file,"<sql-submit></sql-submit><sql-delete></sql-delete>\r\n");
			fwrite($this->form_template_file,"</div>\r\n");   
			fwrite($this->form_template_file,"</div>\r\n");   
			fwrite($this->form_template_file,"</div>\r\n");   
			fwrite($this->form_template_file,"</div>\r\n");   
			fwrite($this->form_template_file,"</sql-form>\r\n"); 
			fwrite($this->form_template_file,"\r\n");
                   
	    }

		
		function create_table_module_min() {
			fwrite($this->table_ts_file, "import { Component } from '@angular/core';\r\n");
			fwrite($this->table_ts_file, "import { CommonModule } from '@angular/common';\r\n");
			fwrite($this->table_ts_file, "import { SqlComponentsModule } from 'sql-components';\r\n");
			fwrite($this->table_ts_file, "import { " . $this->form_class_name . " } from '../" . $this->form_directory . "/" . $this->form_directory . ".component';\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "@Component({\r\n");
			fwrite($this->table_ts_file, "  selector: '" . $this->table_selector . "',\r\n");
			fwrite($this->table_ts_file, "  standalone: true,\r\n");
			fwrite($this->table_ts_file, "  imports: [CommonModule, SqlComponentsModule, " . $this->form_class_name . "], \r\n");
			fwrite($this->table_ts_file, "  templateUrl: './" . $this->table_directory . ".component.html',\r\n");
			fwrite($this->table_ts_file, "  styleUrls: ['./" . $this->table_directory . ".component.css']\r\n");
			fwrite($this->table_ts_file, "})\r\n");
			fwrite($this->table_ts_file, "export class " . $this->table_class_name . " {\r\n");
			fwrite($this->table_ts_file, " \r\n");
			fwrite($this->table_ts_file, "}\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "\r\n");
		}

		function create_list_module_min() {
			fwrite($this->table_ts_file, "import { Component, Input, Output, EventEimtter } from '@angular/core';\r\n");
			fwrite($this->table_ts_file, "import { CommonModule } from '@angular/common';\r\n");
			fwrite($this->table_ts_file, "import { SqlComponentsModule } from 'sql-components';\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "@Component({\r\n");
			fwrite($this->table_ts_file, "  selector: '" . $this->table_selector . "',\r\n");
			fwrite($this->table_ts_file, "  standalone: true,\r\n");
			fwrite($this->table_ts_file, "  imports: [CommonModule, SqlComponentsModule], \r\n");
			fwrite($this->table_ts_file, "  templateUrl: './" . $this->table_directory . ".component.html',\r\n");
			fwrite($this->table_ts_file, "  styleUrls: ['./" . $this->table_directory . ".component.css']\r\n");
			fwrite($this->table_ts_file, "})\r\n");
			fwrite($this->table_ts_file, "export class " . $this->table_class_name . " {\r\n");
			fwrite($this->table_ts_file, " \r\n");
			fwrite($this->table_ts_file, "  @Output() button_click: EventEmitter<any> = new EventEmitter<any>();\r\n");
			fwrite($this->table_ts_file, "  @Output() row_click: EventEmitter<any> = new EventEmitter<any>();\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "processButton(m: any) {\r\n");
			fwrite($this->table_ts_file, " this.button_click.emit(m);\r\n");
			fwrite($this->table_ts_file, "}\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "processRow(m: any) {\r\n");
			fwrite($this->table_ts_file, " this.row_click.emit(m);\r\n");
			fwrite($this->table_ts_file, "}\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "}\r\n");
		}

		function create_query_table_module_min($directory, $selector, $class_name, $sql) {
			fwrite($this->table_ts_file, "import { Component, Input, Output, EventEimtter } from '@angular/core';\r\n");
			fwrite($this->table_ts_file, "import { CommonModule } from '@angular/common';\r\n");
			fwrite($this->table_ts_file, "import { SqlComponentsModule } from 'sql-components';\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "@Component({\r\n");
			fwrite($this->table_ts_file, "  selector: '" . $selector . "',\r\n");
			fwrite($this->table_ts_file, "  standalone: true,\r\n");
			fwrite($this->table_ts_file, "  imports: [CommonModule, SqlComponentsModule, " . $class_name . "], \r\n");
			fwrite($this->table_ts_file, "  templateUrl: './" . $directory . ".component.html',\r\n");
			fwrite($this->table_ts_file, "  styleUrls: ['./" . $directory . ".component.css']\r\n");
			fwrite($this->table_ts_file, "})\r\n");
			fwrite($this->table_ts_file, "export class " . $this->class_name . " {\r\n");
			fwrite($this->table_ts_file, " \r\n");
			fwrite($this->table_ts_file, "  @Output() button_click: EventEmitter<any> = new EventEmitter<any>();\r\n");
			fwrite($this->table_ts_file, "  @Output() row_click: EventEmitter<any> = new EventEmitter<any>();\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "processButton(m: any) {\r\n");
		    fwrite($this->table_ts_file, " this.button_click.emit(m);\r\n");
			fwrite($this->table_ts_file, "}\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "processRow(m: any) {\r\n");
			fwrite($this->table_ts_file, " this.row_click.emit(m);\r\n");
			fwrite($this->table_ts_file, "}\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "}\r\n");
			fwrite($this->table_ts_file, "\r\n");
			fwrite($this->table_ts_file, "&nbsp);\r\n");
		}

		function create_query_table_template_min($sql, $cols) {	
			fwrite($this->table_template_file, "\r\n");
			fwrite($this->table_template_file, "\r\n");
			fwrite($this->table_template_file, "\r\n");
			fwrite($this->table_template_file, "<sql-table [title]=\"'Title Goes Here'\"\r\n");
			fwrite($this->table_template_file, "  [sql]=\"'" . $sql . "'\">\r\n");
			foreach ($cols as $c) {
			   fwrite($this->table_template_file,"<th #column id=\"" . $c['Field'] . "\">" . $c['Field'] . "</th>\r\n");
			}
			fwrite($this->table_template_file, "<button #column class=\"btn btn-primary\"><i class=\"bi bi-clipboard-data\"></i></button;gt;\r\n");
			fwrite($this->table_template_file, "</sql-table>");
		}

		function create_table_template_min() {
			fwrite($this->table_template_file, "<sql-add-table [title]=\"'" . $this->base_table . "'\" [sql]=\"'select * from " . $this->table_name . "'\">\r\n");
			foreach ($this->cols as $c) {
			   fwrite($this->table_template_file,"    <th #column id=\"" . $c['Field'] . "\">" . $c['Field'] . "</th>\r\n");
			}
			fwrite($this->table_template_file, "      <div form>\r\n");
			fwrite($this->table_template_file, "            <" . $this->form_selector . "></" . $this->form_selector . ">\r\n");
			fwrite($this->table_template_file, "      </div>\r\n");
			fwrite($this->table_template_file, "</sql-add-table>");
		}

		function make_crud_set($name) {
			
			$this->table_name = $name;
			$tmp=str_replace("psp_","",$this->table_name);
			$this->base_table = str_replace('_','-',$tmp);
			$this->get_cols($this->table_name);

			$this->form_selector="app-" . $this->base_table . "-form";
			$this->table_selector="app-" . $this->base_table . "-table";
			$this->panel_selector="app-" . $this->base_table . "-panel";
			$this->dashboard_selector="app-" . $this->base_table . "-dashboard";

			$this->form_directory=$this->base_table . '-form';
			try {
				mkdir($this->base_dir . "/" . $this->form_directory);
    		}  catch(Exception $e) { }

            $this->form_ts_file=fopen($this->base_dir . "/" . $this->form_directory . "/" . $this->form_directory . ".component.ts","w");
            $this->form_template_file=fopen($this->base_dir . "/" . $this->form_directory . "/" . $this->form_directory . ".component.html","w");
            $this->form_css_file=fopen($this->base_dir . "/" . $this->form_directory . "/" . $this->form_directory . ".component.css","w");

			array_push($this->post['files'],$this->form_directory . "/" . $this->form_directory . ".component.ts");
			array_push($this->post['files'],$this->form_directory . "/" . $this->form_directory . ".component.html");
			array_push($this->post['files'],$this->form_directory . "/" . $this->form_directory . ".component.css");

			$this->table_directory=$this->base_table . '-table';
			try {
				mkdir($this->base_dir . "/" . $this->table_directory);
    		}  catch(Exception $e) { }

            $this->table_ts_file=fopen($this->base_dir . "/" . $this->table_directory . "/" . $this->table_directory . ".component.ts","w");
            $this->table_template_file=fopen($this->base_dir . "/" . $this->table_directory . "/" . $this->table_directory . ".component.html","w");
            $this->table_css_file=fopen($this->base_dir . "/" . $this->table_directory . "/" . $this->table_directory . ".component.css","w");

			array_push($this->post['files'],$this->table_directory . "/" . $this->table_directory . ".component.ts","w");
			array_push($this->post['files'],$this->table_directory . "/" . $this->table_directory . ".component.html","w");
			array_push($this->post['files'],$this->table_directory . "/" . $this->table_directory . ".component.css","w");

			$this->panel_directory=$this->base_table . '-panel';
			$this->dashboard_directory=$this->base_table . '-dashboard';

			$this->form_class_name=$this->make_class_name($this->base_table,"Form");
			$this->table_class_name=$this->make_class_name($this->base_table,"Table");
			$this->panel_class_name=$this->make_class_name($this->base_table,"Panel");
			$this->dashboard_class_name=$this->make_class_name($this->base_table,"Dashboard");

			$this->create_form_module_min();
			$this->create_form_template_vert3_min();
			$this->create_table_module_min();
			$this->create_table_template_min();
			$this->send_zip();
		}

		function make_dashboard_set($name) {
			
			$this->table_name = $name;
			$tmp=str_replace("psp_","",$this->table_name);
			$this->base_table = str_replace('_','-',$tmp);
			$this->get_cols($this->table_name);

			$this->form_selector="app-" . $this->base_table . "-form";
			$this->table_selector="app-" . $this->base_table . "-list";
			$this->panel_selector="app-" . $this->base_table . "-panel";
			$this->dashboard_selector="app-" . $this->base_table . "-dashboard";

			$this->form_directory=$this->base_table . '-form';
			try {
				mkdir($this->base_dir . "/" . $this->form_directory);
    		}  catch(Exception $e) { }

            $this->form_ts_file=fopen($this->base_dir . "/" . $this->form_directory . "/" . $this->form_directory . ".component.ts","w");
            $this->form_template_file=fopen($this->base_dir . "/" . $this->form_directory . "/" . $this->form_directory . ".component.html","w");
            $this->form_css_file=fopen($this->base_dir . "/" . $this->form_directory . "/" . $this->form_directory . ".component.css","w");

			array_push($this->post['files'],$this->form_directory . "/" . $this->form_directory . ".component.ts");
			array_push($this->post['files'],$this->form_directory . "/" . $this->form_directory . ".component.html");
			array_push($this->post['files'],$this->form_directory . "/" . $this->form_directory . ".component.css");

			$this->table_directory=$this->base_table . '-list';
			try {
				mkdir($this->base_dir . "/" . $this->table_directory);
    		}  catch(Exception $e) { }

            $this->table_ts_file=fopen($this->base_dir . "/" . $this->table_directory . "/" . $this->table_directory . ".component.ts","w");
            $this->table_template_file=fopen($this->base_dir . "/" . $this->table_directory . "/" . $this->table_directory . ".component.html","w");
            $this->table_css_file=fopen($this->base_dir . "/" . $this->table_directory . "/" . $this->table_directory . ".component.css","w");

			array_push($this->post['files'],$this->table_directory . "/" . $this->table_directory . ".component.ts","w");
			array_push($this->post['files'],$this->table_directory . "/" . $this->table_directory . ".component.html","w");
			array_push($this->post['files'],$this->table_directory . "/" . $this->table_directory . ".component.css","w");

			$this->panel_directory=$this->base_table . '-panel';
			try {
				mkdir($this->base_dir . "/" . $this->panel_directory);
    		}   catch(Exception $e) { }

			array_push($this->post['files'],$this->panel_directory . "/" . $this->panel_directory . ".component.ts","w");
			array_push($this->post['files'],$this->panel_directory . "/" . $this->panel_directory . ".component.html","w");
			array_push($this->post['files'],$this->panel_directory . "/" . $this->panel_directory . ".component.css","w");

			$this->dashboard_directory=$this->base_table . '-dashboard';
			try {
				mkdir($this->base_dir . "/" . $this->dashboard_directory);
    		}   catch(Exception $e) { }

			array_push($this->post['files'],$this->dashboard_directory . "/" . $this->dashboard_directory . ".component.ts","w");
			array_push($this->post['files'],$this->dashboard_directory . "/" . $this->dashboard_directory . ".component.html","w");
			array_push($this->post['files'],$this->dashboard_directory . "/" . $this->dashboard_directory . ".component.css","w");


			$this->form_class_name=$this->make_class_name($this->base_table,"Form");
			$this->table_class_name=$this->make_class_name($this->base_table,"List");
			$this->panel_class_name=$this->make_class_name($this->base_table,"Panel");
			$this->dashboard_class_name=$this->make_class_name($this->base_table,"Dashboard");

			$this->create_form_module_min();
			$this->create_form_template_vert3_min();
			$this->create_list_module_min();
			$sql="select * from " . $this->table_name;
			$this->create_query_table_template_min($sql, $this->cols);
			$this->send_zip();
		}

}

$table=$_POST['table_name'];
$sql=$_POST['sql'];
$selector=$_POST['selector'];
$directory_name=$_POST['directory_name'];
$class_name=$_POST['class_name'];
$package=$_POST['package'];

$A=new SQLLabsAI();

if ($package=="add-table") $A->make_crud_set($table);
if ($package=="query-table") $A->make_dashboard_set($table);


//$A->make_dashboard_set($table_name);
//$A->create_form_module_min();
//$A->create_form_template_vert3_min();
//$A->create_table_module_min();
//$A->create_table_template_min();
//$A->send_zip();
//echo "die";

?>
