<? if (eregi('markkatzman.com', $_SERVER['HTTP_HOST'])) require('markkatzman.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Artzar: Art & Literature Showcase</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="keywords" content="art literature photography paintings drawings interviews essays fiction memoirs flash quicktime">
	<meta name="description" content="Features photography, interviews, fiction, paintings, essays, memoirs, and multi-media peices, all displayed within unique gallery designs.">
 	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</head>   
	
	<script language="JavaScript" type="text/javascript">
	<!--
		// This is the address of the latest cover
		var coverSRC = "/content/index.html";
		
		function fillFrame () {
		
			mainFrame.location = coverSRC;
		
		}
		
		function writeFrameset() {

			document.write('<frameset rows="50,*,50" frameborder="NO" border="0" framespacing="0" onLoad="fillFrame()">');
			document.write('  <frame name="topBorder" scrolling="NO" noresize src="/frame/border.html" >');
			document.write('  <frameset cols="50,*,50" frameborder="NO" border="0" framespacing="0">');
			document.write('    <frame name="leftFrame" scrolling="NO" noresize src="/frame/border.html">');
			document.write('    <frame name="mainFrame">');
			document.write('    <frame name="rightFrame" scrolling="NO" noresize src="/frame/border.html">');
			document.write('  </frameset>');
			document.write('  <frame name="bottomFrame" scrolling="NO" noresize src="/frame/title.html">');
			document.write('</frameset>');
		
		}
		
		if (window.location == top.location) writeFrameset(); 

	//-->
	</script>
	


<body>
	<h2>Artzar: Art and Literature Showcase</h2>
	<p>Welcome to Artzar, a web zine of contemporary art
		and literature which features photography, interviews, fiction, paintings,
		essays, memoirs, and multi-media peices, all displayed within unique gallery designs.</p>
	<a href="/content/index.html">Site Contents</a>
</body>

</html>
