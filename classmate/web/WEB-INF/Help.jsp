<!--
Help.jsp
    Display the help document
  
Revision History
    Rajkaran 2013.12.3; Created
-->

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
                        <li><a href="${pageContext.request.contextPath}">Home</a></li>
                        <li class="active"><a href="LoadHelp">Help</a></li>
                      </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>

            <div class="container">
                <div>
                    <h1>User Manual</h1>
                    
                    <h2>How to Create a Note</h2>
                    <ul>
                        <li>Enter the url in the address bar &quot;http://classmate.com&quot;.</li>
                        <li>A blank document will get open.</li>
                        <li>Document will have two fields title and content; title is to give a short description about the document.</li>
                        <li>Basically document can have many type of data such as programming code, images, text and links.</li>
                        <li>On the top right corner of the body you can find 3 buttons to add a block of particular type.</li>
                        <li>So just click on particular button to add that block; and the new block will get focused.</li>
                        <li>Add a link user needs to select a piece of text and a small bubble will show up above the selected text and then user have to click on that button. This will popup a dialog box asking for link url; user have to provide it and click ok.</li>
                        <li>User can rearrange block by dragging a specific block to the appropriate position.</li>
                        <li>System saves the data every time when user stops typing; so user don?t have to worry about losing data.</li>
                    </ul>

                    <h2>How to Share a Note</h2> 
                    <ul>
                        <li>Users can share notes by sending the url of the note to their friends in email.</li>
                        <li>In the right hand side of the header of web page users can find a text field and a button to send emails.</li>
                        <li>User have to provide a correct email and click on ?send email? button; application will send an email consisting url to that note.</li>
                    </ul>

                    <h2>How to Read a Note</h2>
                    <ul>				
                        <li>To read a note user has to enter web sites url with id number of that note.</li>
                        <li>User can directly enter url with note id number if user can remember this. Otherwise user can click on the link that he received in email.</li>
                    </ul>

		</div>

            </div><!-- /.container -->
		
            <script src="js/bootstrap.min.js"></script>
	</body>
</html>