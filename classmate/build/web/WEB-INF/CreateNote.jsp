<!--
CreateNote.jsp
    Allow user to create a new note and share it
  
Revision History
    Rajkaran 2013.11.3; Created
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

        <title>ClassMate | Notes</title>

        <!-- Bootstrap core CSS -->
        <link href="${pageContext.request.contextPath}/assets/css/bootstrap.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="${pageContext.request.contextPath}/assets/css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="${pageContext.request.contextPath}/assets/css/jquery.clrtext.css">
        <link rel="stylesheet" type="text/css" href="${pageContext.request.contextPath}/assets/css/dessert.css" />

        <script type="text/javascript" src="${pageContext.request.contextPath}/assets/js/highlighter/prettify.js"></script>
        <script type="text/javascript" src="${pageContext.request.contextPath}/assets/js/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script type="text/javascript" src="${pageContext.request.contextPath}/assets/js/jquery.clrtext/jquery.clrtext.js"></script>
        <script type="text/javascript" src="${pageContext.request.contextPath}/assets/js/validator.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
               $("#shareNote").on("click", function(e){
                    e.preventDefault();
                    var email = prompt("Recipient's Email ID: ","");
                    var noteId = $("#noteId").val();
                    var validate = new validator();
                    if( !validate.isEmpty(noteId) ){
                        if( !validate.isEmpty( email) && validate.isEmail( email) ){
                            $.post('sendEmail',{emailTo:email, noteId:noteId},function(responseText) {
                                var jsonObj = JSON.parse(responseText);                        
                                $("#emailResponse").html(jsonObj.message);
                                $("#emailResponse").show();
                            });
                        }else {
                            $("#emailResponse").html("Recipient's email id is not in correct format.");
                            $("#emailResponse").show();
                        }
                    }
                    
		});
                
                $("#emailResponse").hide();
                $(".returnedMessage").hide();
                
            });
        </script>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="${pageContext.request.contextPath}">ClassMate</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="${pageContext.request.contextPath}">Home</a></li>
                        <li><a href="LoadHelp">Help</a></li>
                        <li><a href="#contact"  id="shareNote">Share</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

       
        <input type="hidden" id="noteId" value=""/>
                
        <div class="container">
             <div class="returnedMessage" ></div>
              <div id="emailResponse"></div>
            <div class="starter-template">
                <div id="mainContainer"></div>
            </div>
        </div><!-- /.container -->
        
        <script type="text/javascript">
            //initialize clrText
            $('#mainContainer').clrtext({
                url: "createNote"
            });
        </script>
        <script src="${pageContext.request.contextPath}/assets/js/bootstrap.min.js"></script>
    </body>
</html>
