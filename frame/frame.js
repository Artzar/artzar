

function printFrameset () {



mainFrameLoc = "/frame/frame.php?pageurl=" + window.location.pathname + window.location.hash;



document.write('<frameset rows="50,*,50" frameborder="NO" border="0" framespacing="0">');

document.write('  <frame name="topBorder" scrolling="NO" noresize src="/frame/border.html" >');

document.write('  <frameset cols="50,*,50" frameborder="NO" border="0" framespacing="0">');

document.write('    <frame name="leftFrame" scrolling="NO" noresize src="/frame/border.html">');

document.write('    <frame name="mainFrame" src="'+ mainFrameLoc +'">');

document.write('    <frame name="rightFrame" scrolling="NO" noresize src="/frame/border.html">');

document.write('  </frameset>');

document.write('  <frame name="bottomFrame" scrolling="NO" noresize src="/frame/title.html">');

document.write('</frameset>');



document.write('<noframes>');

}



if (window.location == top.location) printFrameset();

