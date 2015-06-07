/* PopulateDatabase.js
	This file includes functions which creates and Populate database downloaded from  server.
	
	Currently this file also includes data that is required for constructing sample application.
*/


//Sql queries to fill theme table with the data required for the sample application theme
var themeDropSql = "DROP table if exists theme";
var themeSql = "CREATE TABLE theme (id int primarykey, property text,value text)";
var themeInsertSql1 = "INSERT INTO theme('id','property','value') VALUES ('1','txtwidth','250')";
var themeInsertSql2 = "INSERT INTO theme('id','property','value') VALUES ('2','txtheight','30')";
var themeInsertSql3 = "INSERT INTO theme('id','property','value') VALUES ('3','lblFontSize','20')";
var themeInsertSql4 = "INSERT INTO theme('id','property','value') "
                        +"VALUES ('4','lblFontFamily','Nyala')";
var themeInsertSql5 = "INSERT INTO theme('id','property','value') VALUES ('5','radSize','1.5')";
var themeInsertSql6 = "INSERT INTO theme('id','property','value') VALUES ('6','chkSize','1.5')";
var themeInsertSql7 = "INSERT INTO theme('id','property','value') VALUES ('7','bgColor','0')";
var themeInsertSql8 = "INSERT INTO theme('id','property','value') "
                        +"VALUES ('8','txtBorderRadius','0')";
var themeInsertSql9 = "INSERT INTO theme('id','property','value') VALUES ('9','sliderWidth','400')";
var themeInsertSql10 = "INSERT INTO theme('id','property','value') "
                         +"VALUES ('10','lblColor','#000000')";
var themeInsertSql11 = "INSERT INTO theme('id','property','value') "
                         +"VALUES ('11','bgImage','img/bg.png')";
var themeInsertSql12 = "INSERT INTO theme('id','property','value') "
                         +"VALUES ('12','txtBgColor','#3d3d3d')";
var themeInsertSql13 = "INSERT INTO theme('id','property','value') "
                         +"VALUES ('13','txtColor','#FFFFFF')";
var themeInsertSql14 = "INSERT INTO theme('id','property','value') "
                         +"VALUES ('14','txtAreaRadius','15')";
var themeInsertSql15 = "INSERT INTO theme('id','property','value') "
                         +"VALUES ('15','txtAreaBorder','3px solid #76d15c')";
var themeInsertSql16 = "INSERT INTO theme('id','property','value') "
                          +"VALUES ('16','belowStripColor','#76d15c')";


//Sql queries to fill slides table with the data required to create Slides in sample application
var slideDropSql = "DROP table if exists slides";
var slideSql = "CREATE TABLE slides (id int primarykey,name text NOT NULL, hasSubSetCheck numeric,"
                                            +"seqNum numeric, partOfSubSet numeric,checkboxID  )";
var slideInsertSql1 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet', "
                            +"'checkboxID') VALUES ('1','chkBoxesKitchen','0','4', '1', 'chk10')";
var slideInsertSql2 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES ('2','welcomeSlide', 0,'2', '0', '0')";
var slideInsertSql3 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES ('3','Sliders','0','11', 0, 0)";
var slideInsertSql4 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES ('4','DNDInfo','0','12', 0, 0)";
var slideInsertSql5 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES ('5','surveyIntro','0','3', 0, 0)";
var slideInsertSql6 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES ('6','textAreaKitchen','0','7', '1', 'chk10')";
var slideInsertSql7 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES ('7','firstSlide','1','1', '0', '0')";
var slideInsertSql8 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES (8,'DNDFirst',0,13,0,0)";
var slideInsertSql9 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES (9,'DNDSecond',0,14,0,0)";
var slideInsertSql10 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES (10,'textAreaBathroom',0,7,1,'chk11')";
var slideInsertSql11 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES (11,'textAreaBasement',0,9,1,'chk12')";
var slideInsertSql12 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES (12,'textAreaConcerns',0,10,0,0)";
var slideInsertSql13 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES (13,'chkBoxesBasement',0,6,1,'chk12')";
var slideInsertSql14 = "INSERT INTO slides('id','name','hasSubSetCheck','seqNum', 'partOfSubSet',"
                            +" 'checkboxID') VALUES (14,'chkBoxesBathroom',0,5,1,'chk11')";
	
//Sql queries to fill controls table with the data required to populate Slides of sample Application
var controlDropSql = "DROP table if exists controls";
var controlSql = "CREATE TABLE controls (id int primarykey,name text,type text,top numeric,"
                                        + "left numeric, isRequired numeric, containSubSet numeric,"
                                        + "slideID int,value text, width numeric, height numeric,"
                                        + " paraSize numeric, forValue numeric, placeHolder text,"
                                        +" paraColor text, FOREIGN KEY(slideID) REFERENCES slides(id) )";
var controlInsertSql1 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('1','para1','para',"
                            + "'120','220','0','0','1', 'Tell us what is not working for you in your"
                            +" Kitchen',650,0,28,0,0,'#76d15c')";
var controlInsertSql2 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('2','para2','para',"
                            + "'150','220','0','0','1', 'Select all items you would like to see"
                            +" addressed',650,0,18,0,0,'#76d15c')";
var controlInsertSql3 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('3','chk1','checkbox'"
                            +",'200','225','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql4 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('4','chk2','checkbox',"
                            +"'260','225','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql5 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('5','chk3','checkbox',"
                            +"'320','225','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql6 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('6','chk4','checkbox'," +
                            "'380','225','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql7 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('7','chk5','checkbox',"
                            +"'200','625','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql8 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('8','chk6','checkbox',"
                            +"'260','625','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql9 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('9','chk7','checkbox',"
                            +"'320','625','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql10 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('10','chk8','checkbox',"
                            +"'380','625','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql11 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('11','chk9','checkbox',"
                            +"'440','225','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql12 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('12','txt1','text',"
                            +"'440','475','0','0','1', 'null',0,0,0,0,0,'null')";
var controlInsertSql13 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('13','lbl1','label',"
                            +"'200','275','0','0','1', 'Insufficient counterpart',0,0,0,3,0,'null')";
var controlInsertSql14 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('14','lbl2','label',"
                            +"'260','275','0','0','1', 'Small work area',0,0,0,4,0,'null')";
var controlInsertSql15 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('15','lbl3','label',"    
                            +"'320','275','0','0','1', 'Ineffective traffic flow',0,0,0,5,0,'null')";
var controlInsertSql16 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('16','lbl4','label',"
                            +"'380','275','0','0','1', 'Space too enclosed',0,0,0,6,0,'null')";
var controlInsertSql17 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('17','lbl5','label',"
                            +"'200','675','0','0','1', 'Lack of storage',0,0,0,7,0,'null')";
var controlInsertSql18 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('18','lbl6','label',"
                            +"'260','675','0','0','1', 'Tired cabinets',0,0,0,8,0,'null')";
var controlInsertSql19 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('19','lbl7','label',"
                            +"'320','675','0','0','1', 'Poor sight lines',0,0,0,9,0,'null')";
var controlInsertSql20 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('20','lbl8','label',"
                            +"'380','675','0','0','1', 'Insufficient lighting',0,0,0,10,0,'null')";
var controlInsertSql21 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('21','lbl9','label',"
                            +"'440','275','0','0','1', 'Others(please specify)',0,0,0,11,0,'null')";
var controlInsertSql22 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('22','companyLogo',"
                            +"'pic','10','10','0','0','1', 'img/SalesMobileLogo.png',196,65,0,0,0,'null')";
var controlInsertSql23 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('23','para3','para',"
                            + "'250','172','0','0','2', 'The following slides contain client "
                            + "testimonials, a series of question and examples of projects we have"
                            + " completed. The responses you provide will help us understand your needs"
                            +" and guide us to better serve you and your family.', 700,0,24,0,0,'null')";
var controlInsertSql24 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('24','para4','para',"
                            +"'190','172','0','0','2', 'Welcome', 450,0,28,0,0,'#76d15c')";
var controlInsertSql25 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('25','companyLogo',"
                            +"'pic','10','10','0','0','2', 'img/SalesMobileLogo.png',196,65,0,0,0,'null')";
var controlInsertSql26 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('26','instructionBox',"
                            +"'pic','390','650','0','0','2', 'img/instruction.png',348,163,0,0,0,'null')";
var controlInsertSql27 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('27','para5','para',"
                            + "'120','162','0','0','3', 'Please adjust the following scales to"
                            +" reflect your requirements.',750,0,28,0,0,'#76d15c')";
var controlInsertSql28 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('28','slider1',"
                            +"'slider','180','162','0','0','3', 'null',700,0,0,0,0,'null')";
var controlInsertSql29 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('29','slider2',"
                            +"'slider','260','162','0','0','3', 'null',700,0,0,0,0,'null')";
var controlInsertSql30 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('30','slider3',"
                            +"'slider','340','162','0','0','3', 'null',700,0,0,0,0,'null')";
var controlInsertSql31 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('31','slider4',"
                            +"'slider','420','162','0','0','3', 'null',700,0,0,0,0,'null')";
var controlInsertSql32 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('32','para6','para',"
                            + "'200','167','0','0','3', 'We have all of the details planned',"
                            +"350,0,18,0,0,'#000')";
var controlInsertSql33 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('33','para7','para',"
                            + "'200','667','0','0','3', 'We require design assistance',"
                            +"350,0,18,0,0,'#000')";
var controlInsertSql34 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('34','para8','para',"
                            +"'280','167','0','0','3', 'Simple and functional',350,0,18,0,0,'#000')";
var controlInsertSql35 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('35','para9','para',"
                            +"'280','697','0','0','3', 'Latest trends and design',350,0,18,0,0,'#000')";
var controlInsertSql36 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('36','para10','para',"
                            + "'360','167','0','0','3', 'We just strated this process',"
                            +"350,0,18,0,0,'#000')";
var controlInsertSql37 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('37','para11','para',"
                            + "'360','607','0','0','3', 'We are confident about what we need',"
                            +"350,0,18,0,0,'#000')";
var controlInsertSql38 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('38','para12','para',"
                            +"'440','167','0','0','3', 'We need ball park figures',350,0,18,0,0,'#000')";
var controlInsertSql39 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('39','para13','para',"
                            +"'440','684','0','0','3', 'We need a detail fixed cost',350,0,18,0,0,'#000')";
var controlInsertSql40 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('40','companyLogo',"
                            +"'pic','10','10','0','0','3', 'img/SalesMobileLogo.png',196,65,0,0,0,'null')";
var controlInsertSql41 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('41','para14','para',"
                            + "'120','112','0','0','4', 'The following collection of slides provide"
                            +" you with examples of projects we have completed.',800,0,24,0,0,'#76d15c')";
var controlInsertSql42 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('42','para15','para',"
                            + "'210','112','0','0','4', 'To help us identify your renovation"
                            + " preferences, simply drag the like button and drop it on anything"
                            + " in the photo that appeals to you. Please drag the right button"
                            +" that corresponds to the feature.',800,0,24,0,0,'#000')";
var controlInsertSql43 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('43','para16','para',"
                            + "'320','112','0','0','4', 'You can move a like button anywhere on the"
                            + " Image. To remove a like button, simply drag it out of the Image.',"
                            +"800,0,24,0,0,'#000')";
var controlInsertSql44 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('44',"
                            + "'likeColourButton','pic','400','272','0','0','4',"
                            +" 'img/LikeColour.png',80,80,0,0,0,'null')";
var controlInsertSql45 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('45',"
                            + "'likeTextureButton','pic','400','472','0','0','4',"
                            +" 'img/LikeTexture.png',80,80,0,0,0,'null')";
var controlInsertSql46 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('46',"
                            + "'likeDesignButton','pic','400','672','0','0','4',"
                            +" 'img/LikeDesign.png',80,80,0,0,0,'null')";
var controlInsertSql47 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('47','companyLogo',"
                            +"'pic','10','10','0','0','4', 'img/SalesMobileLogo.png',196,65,0,0,0,'null')";
var controlInsertSql48 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('48','para17','para',"
                            +"'160','212','0','0','5', 'Survey',30,0,28,0,0,'#76d15c')";
var controlInsertSql49 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('49','para18','para',"
                            + "'200','212','0','0','5', 'Here at Sales Mobile we recognize that all"
                            +" of our clients are unique.',600,0,24,0,0,'#000')";
var controlInsertSql50 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('50','para19','para',"
                            + "'250','212','0','0','5', 'Your response to the following questions will"
                            + " help , give us a better understanding of your wants and needs.',"
                            +"600,0,24,0,0,'#000')";
var controlInsertSql51 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('51','companyLogo',"
                            +"'pic','10','10','0','0','5', 'img/SalesMobileLogo.png',196,65,0,0,0,'null')";
var controlInsertSql52 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('52','para20','para',"
                            + "'120','112','0','0','6', 'What factors influenced you to renovate"
                            +" your kitchen now?',700,0,28,0,0,'#76d15c')";
var controlInsertSql53 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('53','para21','para',"
                            + "'150','112','0','0','6', '(For Eample: functionality, worn-out, dated,"
                            +" space constrictions )',650,0,18,0,0,'#76d15c')";
var controlInsertSql54 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('54','txtArea1',"
                            + "'textarea','180','112','0','0','6', 'null',85,10,0,0,"
                            +"'Touch here to enter','null')";
var controlInsertSql55 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('55','para22','para',"
                            + "'490','170','0','0','7', 'When done please forward to the next slide"
                            +" and hand the tablet over to the client.',800,0,24,0,0,'#76d15c')";
var controlInsertSql56 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('56','para23','para',"
                            + "'300','170','0','0','7', 'Select the type of project your client"
                            +" interested in (Select all that apply).',800,0,24,0,0,'#76d15c')";
var controlInsertSql57 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('57','para24','para',"
                            +"'130','170','0','0','7', 'For Office Use Only',800,0,28,0,0,'#76d15c')";
var controlInsertSql58 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('58','txt2','text',"
                            +"'188','370','1','0','7', 'null',0,0,0,0,0,'null')";
var controlInsertSql59 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('59','txt3','text',"
                            +"'240','370','1','0','7', 'null',0,0,0,0,0,'null')";
var controlInsertSql60 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('60','lbl10','label',"
                            +"'190','170','0','0','7', 'Sales Person&acute;s Name',0,0,0,58,0,'null')";
var controlInsertSql61 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('61','lbl11','label',"
                            +"'242','170','0','0','7', 'Client Name',0,0,0,59,0,'null')";
var controlInsertSql62 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('62','chk10',"
                            +"'checkbox','440','170','0','1','7', 'null',0,0,0,0,0,'null')";
var controlInsertSql63 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('63','chk11',"
                            +"'checkbox','360','170','0','1','7', 'null',0,0,0,0,0,'null')";
var controlInsertSql64 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('64','chk12',"
                            +"'checkbox','400','170','0','1','7', 'null',0,0,0,0,0,'null')";
var controlInsertSql65 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('65','lbl12','label',"
                            +"'440','200','0','0','7', 'Kitchen',0,0,0,62,0,'null')";
var controlInsertSql66 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('66','lbl13','label',"
                            +"'360','200','0','0','7', 'Bathroom',0,0,0,63,0,'null')";
var controlInsertSql67 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            +" 'forValue', 'placeHolder', 'paraColor') VALUES ('67','likecontrol1',"
                            + "'like','0','0','0','0','8', 'img/Depositphotos_3484817_m.jpg',"
                            +"0,0,0,0,0,'null')";
var controlInsertSql68 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('68','likecontrol2',"
                            + "'like','0','0','0','0','9', 'img/Depositphotos_8701914_m.jpg',"
                            +"0,0,0,0,0,'null')";
var controlInsertSql69 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('69','para25','para',"
                            + "'120','112','0','0','10', 'What factors influenced you to renovate"
                            +" your Bathroom now?',700,0,28,0,0,'#76d15c')";
var controlInsertSql70 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('70','para26','para',"
                            + "'150','112','0','0','10', '(For Eample: functionality, worn-out, dated,"
                            +" space constrictions )',650,0,18,0,0,'#76d15c')";
var controlInsertSql71 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('71','txtArea2',"
                            + "'textarea','180','112','0','0','10', 'null',85,10,0,0,"
                            +"'Touch here to enter','null')";
var controlInsertSql72 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('72','para27','para',"
                            + "'120','112','0','0','11', 'What factors influenced you to renovate"
                            +" your Basement now?',700,0,28,0,0,'#76d15c')";
var controlInsertSql73 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('73','para28','para',"
                            + "'150','112','0','0','11', '(For Eample: growing family, need of"
                            +" addition living space )',650,0,18,0,0,'#76d15c')";
var controlInsertSql74 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('74','txtArea3',"
                            + "'textarea','180','112','0','0','11', 'null',85,10,0,0,"
                            +"'Touch here to enter','null')";
var controlInsertSql75 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('75','companyLogo',"
                            + "'pic','10','10','0','0','6', 'img/SalesMobileLogo.png',"
                            +"196,65,0,0,0,'null')";
var controlInsertSql76 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('76','companyLogo',"
                            + "'pic','10','10','0','0','7', 'img/SalesMobileLogo.png',"
                            +"196,65,0,0,0,'null')";
var controlInsertSql77 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('77','companyLogo',"
                            + "'pic','10','10','0','0','10', 'img/SalesMobileLogo.png',"
                            +"196,65,0,0,0,'null')";
var controlInsertSql78 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('78','companyLogo',"
                            + "'pic','10','10','0','0','11', 'img/SalesMobileLogo.png',"
                            +"196,65,0,0,0,'null')";
var controlInsertSql79 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('79','para29','para',"
                            + "'120','112','0','0','12', 'What concerns do you have about renovating?',"
                            +"700,0,28,0,0,'#76d15c')";
var controlInsertSql80 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('80','para30',"
                            + "'para','150','112','0','0','12', '(For Example: timeline,"
                            +" cleanliness, understanding my needs, design )',650,0,18,0,0,'#76d15c')";
var controlInsertSql81 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('81','txtArea4',"
                            + "'textarea','180','112','0','0','12', 'null',85,10,0,0,"
                            +"'Touch here to enter','null')";
var controlInsertSql82 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('82','companyLogo',"
                            + "'pic','10','10','0','0','12', 'img/SalesMobileLogo.png',"
                            +"196,65,0,0,0,'null')";
var controlInsertSql83 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('83','para31','para',"
                            + "'120','220','0','0','13', 'Tell us what is not working for you in"
                            +" your Basement',650,0,28,0,0,'#76d15c')";
var controlInsertSql84 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('84','para32','para',"
                            + "'150','220','0','0','13', 'Select all items you would like to see"
                            +" addressed',650,0,18,0,0,'#76d15c')";
var controlInsertSql85 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('85','chk13',"
                            +"'checkbox','200','225','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql86 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('86','chk14',"
                            +"'checkbox','260','225','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql87 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('87','chk15',"
                            +"'checkbox','320','225','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql88 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('88','chk16',"
                            +"'checkbox','380','225','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql89 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('89','chk17',"
                            +"'checkbox','200','625','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql90 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('90','chk18',"
                            +"'checkbox','260','625','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql91 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('91','chk19',"
                            +"'checkbox','320','625','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql92 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('92','chk20',"
                            +"'checkbox','380','625','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql93 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('93','chk21',"
                            +"'checkbox','440','225','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql94 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('94','txt4',"
                            +"'text','440','475','0','0','13', 'null',0,0,0,0,0,'null')";
var controlInsertSql95 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('95','lbl14',"
                            +"'label','200','275','0','0','13', 'Unfinished space',0,0,0,85,0,'null')";
var controlInsertSql96 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('96','lbl15','label',"
                            +"'260','275','0','0','13', 'Not enough bedrooms',0,0,0,86,0,'null')";
var controlInsertSql97 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('97','lbl16','label',"
                            +"'320','275','0','0','13', 'Not enough bathrooms',0,0,0,87,0,'null')";
var controlInsertSql98 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('98','lbl17','label',"
                            +"'380','275','0','0','13', 'Not enough office space',0,0,0,88,0,'null')";
var controlInsertSql99 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('99','lbl18','label',"
                            +"'200','675','0','0','13', 'Lack of storage',0,0,0,89,0,'null')";
var controlInsertSql100 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('100','lbl19','label',"
                            +"'260','675','0','0','13', 'Not enough room to entertain',0,0,0,90,0,'null')";
var controlInsertSql101 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('101','lbl20','label',"
                            +"'320','675','0','0','13', 'Outdated space',0,0,0,91,0,'null')";
var controlInsertSql102 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('102','lbl21','label',"
                            +"'380','675','0','0','13', 'Insufficient lighting',0,0,0,92,0,'null')";
var controlInsertSql103 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('103','lbl22','label',"
                            +"'440','275','0','0','13', 'Others(please specify)',0,0,0,93,0,'null')";
var controlInsertSql104 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('104','companyLogo',"
                            + "'pic','10','10','0','0','13', 'img/SalesMobileLogo.png',"
                            +"196,65,0,0,0,'null')";
var controlInsertSql105 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('105','para33','para',"
                            + "'120','220','0','0','14', 'Tell us what is not working for you in"
                            +" your Bathroom',650,0,28,0,0,'#76d15c')";
var controlInsertSql106 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('106','para34','para',"
                            + "'150','220','0','0','14', 'Select all items you would like to see"
                            +" addressed',650,0,18,0,0,'#76d15c')";
var controlInsertSql107 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('107','chk22',"
                            +"'checkbox','200','225','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql108 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('108','chk23',"
                            +"'checkbox','260','225','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql109 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            +" 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('109','chk24',"
                            +"'checkbox','320','225','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql110 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('110','chk25',"
                            +"'checkbox','380','225','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql111 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('111','chk26',"
                            +"'checkbox','200','625','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql112 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('112','chk27',"
                            +"'checkbox','260','625','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql113 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('113','chk28',"
                            +"'checkbox','320','625','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql114 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('114','chk29',"
                            +"'checkbox','380','625','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql115 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('115','chk30',"
                            +"'checkbox','440','225','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql116 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('116','txt5','text',"
                            +"'440','475','0','0','14', 'null',0,0,0,0,0,'null')";
var controlInsertSql117 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('117','lbl23',"
                            + "'label','200','275','0','0','14', 'Insufficient counterpart',0,0,"
                            +"0,107,0,'null')";
var controlInsertSql118 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('118','lbl24',"
                            + "'label','260','275','0','0','14', 'Leaking fixtures',"
                            +"0,0,0,108,0,'null')";
var controlInsertSql119 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('119','lbl25','label',"
                            +"'320','275','0','0','14', 'Outdate space',0,0,0,109,0,'null')";
var controlInsertSql120 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('120','lbl26','label',"
                            +"'380','275','0','0','14', 'Space too enclosed',0,0,0,110,0,'null')";
var controlInsertSql121 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('121','lbl27','label',"
                            +"'200','675','0','0','14', 'Lack of storage',0,0,0,111,0,'null')";
var controlInsertSql122 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('122','lbl28','label',"
                            +"'260','675','0','0','14', 'Tired cabinets',0,0,0,112,0,'null')";
var controlInsertSql123 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('123','lbl29','label',"
                            +"'320','675','0','0','14', 'Poor ambiance',0,0,0,113,0,'null')";
var controlInsertSql124 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('124','lbl30','label',"
                            +"'380','675','0','0','14', 'Insufficient lighting',0,0,0,114,0,'null')";
var controlInsertSql125 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('125','lbl31','label',"
                            +"'440','275','0','0','14', 'Others(please specify)',0,0,0,115,0,'null')";
var controlInsertSql126 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('126','companyLogo',"
                            +"'pic','10','10','0','0','14', 'img/SalesMobileLogo.png',196,65,0,0,0,'null')";
var controlInsertSql127 = "INSERT INTO controls('id','name','type','top', 'left', 'isRequired',"
                            + " 'containSubSet','slideID','value', 'width', 'height', 'paraSize',"
                            + " 'forValue', 'placeHolder', 'paraColor') VALUES ('127','lbl32','label',"
                            +"'400','200','0','0','7', 'Basement',0,0,0,64,0,'null')";
							
var surveyInfoDropSql = "DROP table if exists surveyInfo";
var surveyInfoSql = "CREATE TABLE surveyInfo (id int primarykey,startTimeStamp text,endTimeStamp text )";

var surveyDataDropSql = "DROP table if exists surveyData";
var surveyDataSql = "CREATE TABLE surveyData (surveyId int, controId numeric, value text,"
							+" FOREIGN KEY(surveyId) REFERENCES surveyInfo(id) )";

var likeDataDropSql = "DROP table if exists likeData";
var likeDataSql = "CREATE TABLE likeData (surveyId int, likeControId numeric, objectId numeric,"
							+" objectType text, xCoordinate numeric, yCoordinate numeric,"
							+" FOREIGN KEY(surveyId) REFERENCES surveyInfo(id) )";


function successMsg(string){
	//alert("successful"+string);
}

//reload the application when all tables are filled
function success(string){
	window.location.reload(true);
}

function successdrop(string){
	//alert("drop table successful"+string);
}

//throw sql errors 
function onError(tx, error) {
	alert("error:  "+error.message);
}

//Function that execute sql queries to fill required tables
function generateTB(dbObject) {
	dbObject.transaction(
		function (tx) {
			
			tx.executeSql(slideDropSql,[],successMsg,onError);
			tx.executeSql(slideSql,[],successMsg,onError);
			tx.executeSql(slideInsertSql1,[],successMsg,onError);
			tx.executeSql(slideInsertSql2,[],successMsg,onError);
			tx.executeSql(slideInsertSql3,[],successMsg,onError);
			tx.executeSql(slideInsertSql4,[],successMsg,onError);
			tx.executeSql(slideInsertSql5,[],successMsg,onError);
			tx.executeSql(slideInsertSql6,[],successMsg,onError);
			tx.executeSql(slideInsertSql7,[],successMsg,onError);
			tx.executeSql(slideInsertSql8,[],successMsg,onError);
			tx.executeSql(slideInsertSql9,[],successMsg,onError);
			tx.executeSql(slideInsertSql10,[],successMsg,onError);
			tx.executeSql(slideInsertSql11,[],successMsg,onError);
			tx.executeSql(slideInsertSql12,[],successMsg,onError);
			tx.executeSql(slideInsertSql13,[],successMsg,onError);
			tx.executeSql(slideInsertSql14,[],successMsg,onError);
			
			tx.executeSql(controlDropSql,[],successMsg,onError);
			tx.executeSql(controlSql,[],successMsg,onError);
			tx.executeSql(controlInsertSql1,[],successMsg,onError);
			tx.executeSql(controlInsertSql2,[],successMsg,onError);
			tx.executeSql(controlInsertSql3,[],successMsg,onError);
			tx.executeSql(controlInsertSql4,[],successMsg,onError);
			tx.executeSql(controlInsertSql5,[],successMsg,onError);
			tx.executeSql(controlInsertSql6,[],successMsg,onError);
			tx.executeSql(controlInsertSql7,[],successMsg,onError);
			tx.executeSql(controlInsertSql8,[],successMsg,onError);
			tx.executeSql(controlInsertSql9,[],successMsg,onError);
			tx.executeSql(controlInsertSql10,[],successMsg,onError);
			tx.executeSql(controlInsertSql11,[],successMsg,onError);
			tx.executeSql(controlInsertSql12,[],successMsg,onError);
			tx.executeSql(controlInsertSql13,[],successMsg,onError);
			tx.executeSql(controlInsertSql14,[],successMsg,onError);
			tx.executeSql(controlInsertSql15,[],successMsg,onError);
			tx.executeSql(controlInsertSql16,[],successMsg,onError);
			tx.executeSql(controlInsertSql17,[],successMsg,onError);
			tx.executeSql(controlInsertSql18,[],successMsg,onError);
			tx.executeSql(controlInsertSql19,[],successMsg,onError);
			tx.executeSql(controlInsertSql20,[],successMsg,onError);
			tx.executeSql(controlInsertSql21,[],successMsg,onError);
			tx.executeSql(controlInsertSql22,[],successMsg,onError);
			tx.executeSql(controlInsertSql23,[],successMsg,onError);
			tx.executeSql(controlInsertSql24,[],successMsg,onError);
			tx.executeSql(controlInsertSql25,[],successMsg,onError);
			tx.executeSql(controlInsertSql26,[],successMsg,onError);
			tx.executeSql(controlInsertSql27,[],successMsg,onError);
			tx.executeSql(controlInsertSql28,[],successMsg,onError);
			tx.executeSql(controlInsertSql29,[],successMsg,onError);
			tx.executeSql(controlInsertSql30,[],successMsg,onError);
			tx.executeSql(controlInsertSql31,[],successMsg,onError);
			tx.executeSql(controlInsertSql32,[],successMsg,onError);
			tx.executeSql(controlInsertSql33,[],successMsg,onError);
			tx.executeSql(controlInsertSql34,[],successMsg,onError);
			tx.executeSql(controlInsertSql35,[],successMsg,onError);
			tx.executeSql(controlInsertSql36,[],successMsg,onError);
			tx.executeSql(controlInsertSql37,[],successMsg,onError);
			tx.executeSql(controlInsertSql38,[],successMsg,onError);
			tx.executeSql(controlInsertSql39,[],successMsg,onError);
			tx.executeSql(controlInsertSql40,[],successMsg,onError);
			tx.executeSql(controlInsertSql41,[],successMsg,onError);
			tx.executeSql(controlInsertSql42,[],successMsg,onError);
			tx.executeSql(controlInsertSql43,[],successMsg,onError);
			tx.executeSql(controlInsertSql44,[],successMsg,onError);
			tx.executeSql(controlInsertSql45,[],successMsg,onError);
			tx.executeSql(controlInsertSql46,[],successMsg,onError);
			tx.executeSql(controlInsertSql47,[],successMsg,onError);
			tx.executeSql(controlInsertSql48,[],successMsg,onError);
			tx.executeSql(controlInsertSql49,[],successMsg,onError);
			tx.executeSql(controlInsertSql50,[],successMsg,onError);
			tx.executeSql(controlInsertSql51,[],successMsg,onError);
			tx.executeSql(controlInsertSql52,[],successMsg,onError);
			tx.executeSql(controlInsertSql53,[],successMsg,onError);
			tx.executeSql(controlInsertSql54,[],successMsg,onError);
			tx.executeSql(controlInsertSql55,[],successMsg,onError);
			tx.executeSql(controlInsertSql56,[],successMsg,onError);
			tx.executeSql(controlInsertSql57,[],successMsg,onError);
			tx.executeSql(controlInsertSql58,[],successMsg,onError);
			tx.executeSql(controlInsertSql59,[],successMsg,onError);
			tx.executeSql(controlInsertSql60,[],successMsg,onError);
			tx.executeSql(controlInsertSql61,[],successMsg,onError);
			tx.executeSql(controlInsertSql62,[],successMsg,onError);
			tx.executeSql(controlInsertSql63,[],successMsg,onError);
			tx.executeSql(controlInsertSql64,[],successMsg,onError);
			tx.executeSql(controlInsertSql65,[],successMsg,onError);
			tx.executeSql(controlInsertSql66,[],successMsg,onError);
			tx.executeSql(controlInsertSql67,[],successMsg,onError);
			tx.executeSql(controlInsertSql68,[],successMsg,onError);
			tx.executeSql(controlInsertSql69,[],successMsg,onError);
			tx.executeSql(controlInsertSql70,[],successMsg,onError);
			tx.executeSql(controlInsertSql71,[],successMsg,onError);
			tx.executeSql(controlInsertSql72,[],successMsg,onError);
			tx.executeSql(controlInsertSql73,[],successMsg,onError);
			tx.executeSql(controlInsertSql74,[],successMsg,onError);
			tx.executeSql(controlInsertSql75,[],successMsg,onError);
			tx.executeSql(controlInsertSql76,[],successMsg,onError);
			tx.executeSql(controlInsertSql77,[],successMsg,onError);
			tx.executeSql(controlInsertSql78,[],successMsg,onError);
			tx.executeSql(controlInsertSql79,[],successMsg,onError);
			tx.executeSql(controlInsertSql80,[],successMsg,onError);
			tx.executeSql(controlInsertSql81,[],successMsg,onError);
			tx.executeSql(controlInsertSql82,[],successMsg,onError);
			tx.executeSql(controlInsertSql83,[],successMsg,onError);
			tx.executeSql(controlInsertSql84,[],successMsg,onError);
			tx.executeSql(controlInsertSql85,[],successMsg,onError);
			tx.executeSql(controlInsertSql86,[],successMsg,onError);
			tx.executeSql(controlInsertSql87,[],successMsg,onError);
			tx.executeSql(controlInsertSql88,[],successMsg,onError);
			tx.executeSql(controlInsertSql89,[],successMsg,onError);
			tx.executeSql(controlInsertSql90,[],successMsg,onError);
			tx.executeSql(controlInsertSql91,[],successMsg,onError);
			tx.executeSql(controlInsertSql92,[],successMsg,onError);
			tx.executeSql(controlInsertSql93,[],successMsg,onError);
			tx.executeSql(controlInsertSql94,[],successMsg,onError);
			tx.executeSql(controlInsertSql95,[],successMsg,onError);
			tx.executeSql(controlInsertSql96,[],successMsg,onError);
			tx.executeSql(controlInsertSql97,[],successMsg,onError);
			tx.executeSql(controlInsertSql98,[],successMsg,onError);
			tx.executeSql(controlInsertSql99,[],successMsg,onError);
			tx.executeSql(controlInsertSql100,[],successMsg,onError);
			tx.executeSql(controlInsertSql101,[],successMsg,onError);
			tx.executeSql(controlInsertSql102,[],successMsg,onError);
			tx.executeSql(controlInsertSql103,[],successMsg,onError);
			tx.executeSql(controlInsertSql104,[],successMsg,onError);
			tx.executeSql(controlInsertSql105,[],successMsg,onError);
			tx.executeSql(controlInsertSql106,[],successMsg,onError);
			tx.executeSql(controlInsertSql107,[],successMsg,onError);
			tx.executeSql(controlInsertSql108,[],successMsg,onError);
			tx.executeSql(controlInsertSql109,[],successMsg,onError);
			tx.executeSql(controlInsertSql110,[],successMsg,onError);
			tx.executeSql(controlInsertSql111,[],successMsg,onError);
			tx.executeSql(controlInsertSql112,[],successMsg,onError);
			tx.executeSql(controlInsertSql113,[],successMsg,onError);
			tx.executeSql(controlInsertSql114,[],successMsg,onError);
			tx.executeSql(controlInsertSql115,[],successMsg,onError);
			tx.executeSql(controlInsertSql116,[],successMsg,onError);
			tx.executeSql(controlInsertSql117,[],successMsg,onError);
			tx.executeSql(controlInsertSql118,[],successMsg,onError);
			tx.executeSql(controlInsertSql119,[],successMsg,onError);
			tx.executeSql(controlInsertSql120,[],successMsg,onError);
			tx.executeSql(controlInsertSql121,[],successMsg,onError);
			tx.executeSql(controlInsertSql122,[],successMsg,onError);
			tx.executeSql(controlInsertSql123,[],successMsg,onError);
			tx.executeSql(controlInsertSql124,[],successMsg,onError);
			tx.executeSql(controlInsertSql125,[],successMsg,onError);
			tx.executeSql(controlInsertSql126,[],successMsg,onError);
			tx.executeSql(controlInsertSql127,[],successMsg,onError);
			
			tx.executeSql(themeDropSql,[],successMsg,onError);
			tx.executeSql(themeSql,[],successMsg,onError);
			tx.executeSql(themeInsertSql1,[],successMsg,onError);
			tx.executeSql(themeInsertSql2,[],successMsg,onError);
			tx.executeSql(themeInsertSql3,[],successMsg,onError);
			tx.executeSql(themeInsertSql4,[],successMsg,onError);
			tx.executeSql(themeInsertSql5,[],successMsg,onError);
			tx.executeSql(themeInsertSql6,[],successMsg,onError);
			tx.executeSql(themeInsertSql7,[],successMsg,onError);
			tx.executeSql(themeInsertSql8,[],successMsg,onError);
			tx.executeSql(themeInsertSql9,[],successMsg,onError);
			tx.executeSql(themeInsertSql10,[],successMsg,onError);
			tx.executeSql(themeInsertSql11,[],successMsg,onError);
			tx.executeSql(themeInsertSql12,[],successMsg,onError);
			tx.executeSql(themeInsertSql13,[],successMsg,onError);
			tx.executeSql(themeInsertSql14,[],successMsg,onError);
			tx.executeSql(themeInsertSql15,[],successMsg,onError);
			tx.executeSql(themeInsertSql16,[],success,onError);
			
			tx.executeSql(surveyInfoDropSql,[],successMsg,onError);
			tx.executeSql(surveyInfoSql,[],successMsg,onError);
			
			tx.executeSql(surveyDataDropSql,[],successMsg,onError);
			tx.executeSql(surveyDataSql,[],successMsg,onError);
			
			tx.executeSql(likeDataDropSql,[],successMsg,onError);
			tx.executeSql(likeDataSql,[],successMsg,onError);
			
		}
	);
}
