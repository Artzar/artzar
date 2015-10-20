
<HTML>

<BODY>

<SCRIPT>

<%

  Dim inImageID

  inImageID = Request.QueryString("image")

  if inImageID = "" then
  inImageID = "[blank]"
  end if

  inImageID = Replace(inImageID, " ", "-")

  dim numvisits
  response.cookies("NumVisits1-0").Expires=date+365
  numvisits=request.cookies("NumVisits1-0")
  if numvisits="" then
    numvisits=0
  end if
  numvisits = numvisits + 1
  response.cookies("NumVisits1-0")=numvisits



  Dim logFile, theFSO, fileName, fileOutput

  Set theFSO = CreateObject("Scripting.FileSystemObject")

  fileName = "D:\www\artzar\artzar.com\www\winamp3\1-0\gallery.log"

  fileOutput = inImageID & " " & FormatDateTime(Date(), 0) & " " & FormatDateTime(Time(), 4) & " " & Request.ServerVariables("remote_addr") & " " & numvisits


  If theFSO.FileExists(fileName) Then



    Set logFile = theFSO.OpenTextFile(fileName, 8)

    logFile.WriteLine fileOutput

  End If




  Dim siteRoot, contentDir

  siteRoot = "http://www.artzar.com/"

  contentDir = "content/"

  Select Case inImageID
	Case "bourgeois-poles"
		Response.Write "document.location='" & siteRoot & contentDir & "bourgeois/';"
	Case "bourgeois-spider"
		Response.Write "document.location='" & siteRoot & contentDir & "bourgeois/';"
	Case "carlough-facefan"
		Response.Write "document.location='" & siteRoot & contentDir & "carlough/';"
	Case "carlough-swamp"
		Response.Write "document.location='" & siteRoot & contentDir & "carlough/';"
	Case "close-smile"
		Response.Write "document.location='" & siteRoot & contentDir & "close/';"
	Case "close-overview"
		Response.Write "document.location='" & siteRoot & contentDir & "close/';"
	Case "collector-etheric"
		Response.Write "document.location='" & siteRoot & contentDir & "toltec/';"
	Case "collector-quetzalcoatll"
		Response.Write "document.location='" & siteRoot & contentDir & "toltec/';"
	Case "collector-kali"
		Response.Write "document.location='" & siteRoot & contentDir & "goddess/';"
	Case "collector-neargrover"
		Response.Write "document.location='" & siteRoot & contentDir & "goddess/';"
	Case "fuoco-mountains"
		Response.Write "document.location='" & siteRoot & contentDir & "landscapes/';"
	Case "fuoco-untitled"
		Response.Write "document.location='" & siteRoot & contentDir & "landscapes/';"
	Case "gurche-column"
		Response.Write "document.location='" & siteRoot & contentDir & "gurche/';"
	Case "gurche-diplodocus"
		Response.Write "document.location='" & siteRoot & contentDir & "gurche/';"
	Case "hinsdale-geometry"
		Response.Write "document.location='" & siteRoot & contentDir & "ppt/';"
	Case "hinsdale-rabbi"
		Response.Write "document.location='" & siteRoot & contentDir & "ppt/';"
	Case "mccaffrey-bee"
		Response.Write "document.location='" & siteRoot & contentDir & "myopia/';"
	Case "mccaffrey-lichen"
		Response.Write "document.location='" & siteRoot & contentDir & "myopia/';"
	Case "mcinnis-figure"
		Response.Write "document.location='" & siteRoot & contentDir & "sagrada/';"
	Case "mcinnis-cross"
		Response.Write "document.location='" & siteRoot & contentDir & "sagrada/';"
	Case "mochi-koala"
		Response.Write "document.location='" & siteRoot & contentDir & "mochi/';"
	Case "mochi-mountain"
		Response.Write "document.location='" & siteRoot & contentDir & "mochi/';"
	Case "parnau-grandcentral"
		Response.Write "document.location='" & siteRoot & contentDir & "parnau/';"
	Case "parvez-caravan"
		Response.Write "document.location='" & siteRoot & contentDir & "sahara/';"
	Case "parvez-tree"
		Response.Write "document.location='" & siteRoot & contentDir & "sahara/';"
	Case "saylor-alarm"
		Response.Write "document.location='" & siteRoot & contentDir & "grit/';"
	Case "saylor-noperfection"
		Response.Write "document.location='" & siteRoot & contentDir & "grit/';"
	Case "scott-female"
		Response.Write "document.location='" & siteRoot & contentDir & "portraits/';"
	Case "scott-male"
		Response.Write "document.location='" & siteRoot & contentDir & "portraits/';"
	Case "zorn-flower"
		Response.Write "document.location='" & siteRoot & contentDir & "zorn/';"
	Case Else
		Response.Write "document.location='http://www.artzar.com/';"
  End Select



%>

</SCRIPT>

</BODY>

</HTML>