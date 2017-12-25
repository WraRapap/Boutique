<?php 
$widgets["sticky1"] = array(
	"title" => "資料表管理",
	"model" => "Sticky"
);
$widgets["sticky2"] = array(
	"title" => "資料表管理",
	"model" => "Sticky"
);
$widgets["sticky3"] = array(
	"title" => "資料表管理",
	"model" => "Sticky"
);
$widgets["widget-manager"] = array(
	"title" => "資料表管理",
	"model" => "Datamanager",
	"data_source" => "db:widget",
	"item_setting" => json_encode(array("ADD","FIX","DEL")),
	"item_field_setting" => array(

		array(
			"name" => "識別碼",
			"variable" => "id",
			"type" => "Hidden",
			"properties" => array("max-length" => 40),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "基本設定",
			"variable" => "base_information",
			"type" => "Grouplabel",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "插件名稱",
			"variable" => "title",
			"type" => "Text",
			"properties" => array("required" => true),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		),

		array(
			"name" => "插件模組",
			"variable" => "model",
			"type" => "Select",
			"properties" => array("required" => true,"data_source"=>"Datamanager:資料管理,Sticky:便利貼"),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		),

		array(
			"name" => "資料來源",
			"variable" => "data_source",
			"type" => "Text",
			"properties" => array("required" => true),
			"default" => "",
			"tip" => "此名稱為資料表名稱，不需加前輟詞(e.g., web_)",
			"list" => "Y"
		),

		array(
			"name" => "表單頁",
			"variable" => "form_page",
			"type" => "Toggle",
			"properties" => array("data_source" => "是,否"),
			"default" => "",
			"tip" => "選擇「是」，則此資訊僅做為表單設定，不會有清單",
			"list" => "Y"
		),

		array(
			"name" => "自動產生資料表",
			"variable" => "auto_create_table",
			"type" => "Toggle",
			"properties" => array("data_source" => "是,否"),
			"default" => "",
			"tip" => "選擇「是」，將依欄位設定的資訊來產生資料表",
			"list" => ""
		),

		array(
			"name" => "分類設定",
			"variable" => "class_information",
			"type" => "Grouplabel",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "分類層數",
			"variable" => "class_level",
			"type" => "Integer",
			"properties" => array(),
			"default" => "0",
			"tip" => "此資料可分為幾層分類",
			"list" => ""
		),

		array(
			"name" => "動態層數",
			"variable" => "dynamic_level",
			"type" => "Toggle",
			"properties" => array("data_source" => "是,否"),
			"default" => "",
			"tip" => "選擇「是」，則不限定最後一層才可放項目",
			"list" => ""
		),

		array(
			"name" => "每頁筆數",
			"variable" => "class_count",
			"type" => "Integer",
			"properties" => array(),
			"default" => "10",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "權限設定",
			"variable" => "class_setting",
			"type" => "Checkbox",
			"properties" => array(
				"data_source" => "ADD:新增,FIX:修改,DEL:刪除,VIEW:檢視,CLONE:複製,SORT:排序,PUBLISH:上下架,EXPORT:匯出,PRINT:列印",
				"color" => "blue",
				"type" => "flat"
			),
			"default" => "ADD,FIX,DEL,SORT,PUBLISH",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "欄位設定",
			"variable" => "class_field_setting",
			"type" => "Multicolumns",
			"properties" => array(
				"fields" => "欄位名稱:title:Text,欄位變數:variable:Text,元件:type:Componentlist:data_source{components};,控制元素:properties:Text,預設值:default:Text,提示說明:tip:Text,清單顯示:list:Select:data_source{N#否*Y#是};"
			),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "特殊分類設定",
			"variable" => "special_class_information",
			"type" => "Grouplabel",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "所在分類層數",
			"variable" => "special_class_level",
			"type" => "Integer",
			"properties" => array(),
			"default" => "0",
			"tip" => "在此指定的層數可使用自訂欄位",
			"list" => ""
		),

		array(
			"name" => "權限設定",
			"variable" => "special_class_setting",
			"type" => "Checkbox",
			"properties" => array(
				"data_source" => "ADD:新增,FIX:修改,DEL:刪除,VIEW:檢視,CLONE:複製,SORT:排序,PUBLISH:上下架,EXPORT:匯出,PRINT:列印",
				"color" => "blue",
				"type" => "flat"
			),
			"default" => "ADD,FIX,DEL,SORT,PUBLISH",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "欄位設定",
			"variable" => "special_class_field_setting",
			"type" => "Multicolumns",
			"properties" => array(
				"fields" => "欄位名稱:title:Text,欄位變數:variable:Text,元件:type:Componentlist:data_source{components};,控制元素:properties:Text,預設值:default:Text,提示說明:tip:Text,清單顯示:list:Select:data_source{N#否*Y#是};"
			),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "項目設定",
			"variable" => "item_information",
			"type" => "Grouplabel",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "項目數量限制",
			"variable" => "item_max_count",
			"type" => "Integer",
			"properties" => array(),
			"default" => "0",
			"tip" => "每個分類最多可新增幾個項目(0：無限, -1:不需要項目)",
			"list" => ""
		),

		array(
			"name" => "每頁筆數",
			"variable" => "item_count",
			"type" => "Integer",
			"properties" => array(),
			"default" => "10",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "權限設定",
			"variable" => "item_setting",
			"type" => "Checkbox",
			"properties" => array(
				"data_source" => "ADD:新增,FIX:修改,DEL:刪除,VIEW:檢視,CLONE:複製,SORT:排序,PUBLISH:上下架,EXPORT:匯出,PRINT:列印",
				"color" => "blue",
				"type" => "flat"
			),
			"default" => "ADD,FIX,DEL,SORT,PUBLISH",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "欄位設定",
			"variable" => "item_field_setting",
			"type" => "Multicolumns",
			"properties" => array(
				"fields" => "欄位名稱:title:Text,欄位變數:variable:Text,元件:type:Componentlist:data_source{components};,控制元素:properties:Text,預設值:default:Text,提示說明:tip:Text,清單顯示:list:Select:data_source{N#否*Y#是};"
			),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "測試",
			"variable" => "test",
			"type" => "Date",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => ""
		),
	)

);



$widgets["page-manager"] = array(
	"title" => "頁面管理",
	"model" => "Datamanager",
	"data_source" => "db:page",
	"class_level" => 1,
	"class_setting" => json_encode(array("ADD","FIX","DEL")),
	"class_field_setting" => array(
		array(
			"name" => "識別碼",
			"variable" => "id",
			"type" => "Hidden",
			"properties" => array("max-length" => 40),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "插件代碼",
			"variable" => "widget_id",
			"type" => "Widgetlist",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		)
	),
	"item_setting" => json_encode(array("ADD","FIX","DEL")),
	"item_field_setting" => array(
		array(
			"name" => "識別碼",
			"variable" => "id",
			"type" => "Hidden",
			"properties" => array("max-length" => 40),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "插件代碼",
			"variable" => "widget_id",
			"type" => "Widgetlist",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		),

		array(
			"name" => "是否顯示",
			"variable" => "display",
			"type" => "Toggle",
			"properties" => array("data_source" => "是,否"),
			"default" => "",
			"tip" => "選擇否，插件將不會顯示在頁面上",
			"list" => "Y"
		),

		array(
			"name" => "桌面寬度比",
			"variable" => "desktop",
			"type" => "Select",
			"properties" => array("data_source"=>"12:100%,11:92%,10:84%,9:75%,8:66%,7:58%,6:50%,5:41%,4:33%,3:25%,2:16%,1:8%"),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		),

		array(
			"name" => "平板寬度比",
			"variable" => "pad",
			"type" => "Select",
			"properties" => array("data_source"=>"12:100%,11:92%,10:84%,9:75%,8:66%,7:58%,6:50%,5:41%,4:33%,3:25%,2:16%,1:8%"),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		),

		array(
			"name" => "手機寬度比",
			"variable" => "phone",
			"type" => "Select",
			"properties" => array("data_source"=>"12:100%,11:92%,10:84%,9:75%,8:66%,7:58%,6:50%,5:41%,4:33%,3:25%,2:16%,1:8%"),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		)
	)

);


$widgets["menu-manager"] = array(
	"title" => "選單管理",
	"model" => "Datamanager",
	"data_source" => "db:menu",
	"item_setting" => json_encode(array("CLASS_ADD", "ADD","FIX","DEL")),
	"item_field_setting" => array(
		array(
			"name" => "識別碼",
			"variable" => "id",
			"type" => "Hidden",
			"properties" => array("max-length" => 40),
			"default" => "",
			"tip" => "",
			"list" => ""
		),

		array(
			"name" => "頁面代碼",
			"variable" => "page_id",
			"type" => "Text",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => "Y"
		),

		array(
			"name" => "是否顯示",
			"variable" => "display",
			"type" => "Toggle",
			"properties" => array("data_source" => "是,否"),
			"default" => "",
			"tip" => "選擇否，插件將不會顯示在頁面上",
			"list" => "Y"
		),

		array(
			"name" => "圖示",
			"variable" => "icon",
			"type" => "Text",
			"properties" => array(),
			"default" => "",
			"tip" => "",
			"list" => ""
		)

		
	)

);

/**
 * 頁面設定，可設定有哪些 Widget
 */
$pages["widget-model"] = array(
	"title" => "插件管理",
	"widgets" => array(
		array(
			"id" => "sticky1",
			"display" => "show",
			"desktop" => "4",
			"pad" => "4",
			"phone" => "12",
		),
		array(
			"id" => "sticky2",
			"display" => "show",
			"desktop" => "4",
			"pad" => "4",
			"phone" => "12",
		),
		array(
			"id" => "sticky3",
			"display" => "show",
			"desktop" => "4",
			"pad" => "4",
			"phone" => "12",
		),
		array(
			"id" => "widget-manager",
			"display" => "show",
			"desktop" => "12",
			"pad" => "12",
			"phone" => "12",
		),
		
	)
);

$pages["page-model"] = array(
	"title" => "頁面管理",
	"widgets" => array(
		array(
			"id" => "page-manager",
			"display" => "show",
			"desktop" => "12",
			"pad" => "12",
			"phone" => "12",
		),
		
	)
);

$pages["menu-model"] = array(
	"title" => "選單管理",
	"widgets" => array(
		array(
			"id" => "menu-manager",
			"display" => "show",
			"desktop" => "12",
			"pad" => "12",
			"phone" => "12",
		),
		
	)
);


$menu = array(
	array(
		"icon" => "fa fa-th",
		"title" => "插件管理",
		"page" => "widget-model"
	),
	array(
		"icon" => "fa fa-file-o",
		"title" => "頁面管理",
		"page" => "page-model"
	),
	array(
		"icon" => "fa fa-th-list",
		"title" => "選單管理",
		"page" => "menu-model"
	),
	array(
		"icon" => "fa fa-envelope",
		"title" => "信件設定",
		"page" => "email-model"
	),
	array(
		"icon" => "fa fa-clone",
		"title" => "信件樣版",
		"page" => "emailtemplate-model"
	),

);

$components = array(
	array(
		"title" => "一般元件",
		"components" => array(
			array("title" => "文字"		, "variable" => "Text"),
			array("title" => "文字方塊"	, "variable" => "TextArea"),
			array("title" => "密碼"		, "variable" => "Password"),
			array("title" => "核取方塊"	, "variable" => "CheckBox"),
			array("title" => "單選方塊"	, "variable" => "Radio"),
			array("title" => "單選下拉"	, "variable" => "Select"),
			array("title" => "標籤"		, "variable" => "Label"),
			array("title" => "群組標籤"	, "variable" => "Label_Group"),
			array("title" => "隱藏"		, "variable" => "Hidden")
		)
	),
	
	array(
		"title" => "上傳元件",
		"components" => array(
			array("title" => "圖片上傳"	, "variable" => "Image"),
			array("title" => "檔案上傳"	, "variable" => "File"),
			array("title" => "匯入元件"	, "variable" => "Import")
		)
	),
	
	array(
		"title" => "jQuery元件",
		"components" => array(
			array("title" => "開關閥"		, "variable" => "Switch"),
			array("title" => "置頂元件(開關閥)"		, "variable" => "Top"),
			array("title" => "多選下拉"	, "variable" => "MultiSelect"),
			array("title" => "日期元件"	, "variable" => "jQueryDate"),
			array("title" => "台灣地址"	, "variable" => "Address")
		)
	),
	
	array(
		"title" => "TinyMCE",
		"components" => array(
			array("title" => "編輯器"		, "variable" => "HtmlEditor"),
			array("title" => "圖片裁切"	, "variable" => "ImageSplit"),
			array("title" => "媒體中心"	, "variable" => "MediaCenter")
		)
	),
	
	array(
		"title" => "HTML5元件",
		"components" => array(
			array("title" => "數字"		, "variable" => "Number"),
			array("title" => "網址"		, "variable" => "Url"),
			array("title" => "信箱"		, "variable" => "Email"),
			array("title" => "日期元件"	, "variable" => "Html5Date")
		)
	),
	
	array(
		"title" => "購物商品元件",
		"components" => array(
			array("title" => "商品規格(含材質圖片)"		, "variable" => "FieldsSpec"),
			array("title" => "購物車"		, "variable" => "Cart"),
		)
	),
	array(
		"title" => "自訂元件",
		"components" => array(
			array("title" => "欄位取得規則"				, "variable" => "FieldsPattern"),
			array("title" => "RE規則"					, "variable" => "Pattern"),
			array("title" => "Landing Page選項"		, "variable" => "Landing_Page_Select"),
			array("title" => "Landing Page樣版"		, "variable" => "Landing_Page_Template"),
			array("title" => "購物品項資訊"				, "variable" => "FieldsCart"),
		)
	),
);
?>