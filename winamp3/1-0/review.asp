
<HTML>

<BODY>


<%


  Function getImageClickOrder()

    dim aImageKeys

    aImageKeys = dImageTotals.Keys

    For i=Lbound(aImageKeys) to Ubound(aImageKeys)

	  Dim maxVal

	  maxVal = i

      For j=i to Ubound(aImageKeys)

        If dImageTotals.Item(aImageKeys(j)) > dImageTotals.Item(aImageKeys(maxVal)) then

          maxVal = j

		End If

      Next

      Dim temp

      temp = aImageKeys(i)

      aImageKeys(i) = aImageKeys(maxVal)

      aImageKeys(maxVal) = temp

    Next

    getImageClickOrder = aImageKeys

  End Function

%>

<%


  Dim total, totalVisitors, dImageTotals, aClicksPerVisitor(100), maxCPV, dayTotal, dayNewVisitors, weekTotal, weekNewVisitors

  total = 0

  totalVisitors = 0

  maxCPV = 1

  dayTotal = 0

  dayNewVisitors = 0

  weekTotal = 0

  weekNewVisitors = 0

  set dImageTotals = server.createObject("Scripting.Dictionary")



  Dim logFile, theFSO, fileName, currLine

  Set theFSO = CreateObject("Scripting.FileSystemObject")

  fileName = "D:\www\artzar\artzar.com\www\winamp3\1-0\gallery.log"

  'fileOutput = inImageID & " " & FormatDateTime(Date(), 0) & " " & FormatDateTime(Time(), 4) & " " & Request.ServerVariables("remote_addr") & " " & numvisits'


  If theFSO.FileExists(fileName) Then

    Set logFile = theFSO.OpenTextFile(fileName, 1)

    While Not logFile.atEndOfStream

      currLine = logFile.ReadLine

      call ProcessLine

    Wend

    call WriteResults

  End If



  Sub ProcessLine

    total = total + 1

    Dim aResults, theId

    aResults = split(currLine," ")

  If IsDate(aResults(1)) AND IsDate(aResults(2)) AND IsNumeric(aResults(4)) Then

    Dim theImage, theDate, theTime, theVisit

    theImage = aResults(0)

	theDate = CDate(aResults(1))

    theTime = CDate(aResults(2))

    theVisit = CInt(aResults(4))


    If theVisit = 1 Then

      totalVisitors = totalVisitors + 1

    End If





    If theDate = Date() OR (theDate = Date() - 1 AND theTime > Time()) then

      dayTotal = dayTotal + 1

      If theVisit = 1 Then

        dayNewVisitors = dayNewVisitors + 1

      End If

    End If


    If theDate > Date() - 6 OR (theDate = Date() - 7 AND theTime > Time()) then

      weekTotal = weekTotal + 1

      If theVisit = 1 Then

        weekNewVisitors = weekNewVisitors + 1

      End If

    End If


    aClicksPerVisitor(aResults(4)) = aClicksPerVisitor(aResults(4)) + 1

    if CInt(aResults(4)) > maxCPV then

	  maxCPV = CInt(aResults(4))

    end if



    theId = aResults(0)

    If dImageTotals.Exists(theId) then

      dImageTotals.Item(theId) = dImageTotals.Item(theId) + 1

    Else

      dImageTotals.Add theId,1

    End If

  Else
  
    Response.Write "<p>Error Line: " & total & "</p>"
  
  End If
  
  End Sub




  Sub WriteResults

  	Response.Write "<TABLE width=700><TR><TD>"

    Response.Write "<p>Total Visitors: " & totalVisitors & "</p>"

    Response.Write "<p>Total Clicks: " & total & "</p>"

  	Response.Write "</TD><TD>"

    Response.Write "<p>New Visitors in Past Day: " & dayNewVisitors & "</p>"

    Response.Write "<p>Total Clicks in Past Day: " & dayTotal & "</p>"

  	Response.Write "</TD><TD>"

    Response.Write "<p>New Visitors in Past Week: " & weekNewVisitors & "</p>"

    Response.Write "<p>Total Clicks in Past Week: " & weekTotal & "</p>"

  	Response.Write "</TD></TR></TABLE>"

Response.Write "<br><TABLE><TR><TD valign='top'>"

    Response.Write "<TABLE><TR><TH align=left>Image</TH><TH>Clicks</TH></TR>"

    dim aClickOrder

    aClickOrder = getImageClickOrder()

    For I=0 to Ubound(aClickOrder)
      
	  If dImageTotals.Item(aClickOrder(I)) > 200 Then
	  
	    Response.write "<TR><TD>" & aClickOrder(I) & "</TD><TD align=center>" & dImageTotals.Item(aClickOrder(I)) & "</TR>"

      End If

	Next

	Response.Write "</TABLE></td><td width=60>&nbsp;</td>"

    ' write out the random stuff people do

    Response.Write "<td valign='top'><TABLE><tr><th align='left'>Things people have put <br>in their copy of the skin:</th></tr>"

    For I=0 to Ubound(aClickOrder)
      
	  If dImageTotals.Item(aClickOrder(I)) < 200 Then
	  
	    Response.write "<TR><TD>" & aClickOrder(I) & "</TD></TR>"

      End If

	Next

	Response.Write "</TABLE></td><td width=60>&nbsp;</td>"


	call repairClicksPerVisitor

    Response.Write "<td valign='top'><TABLE width=200><TR><TH>Clicks/Visitor</TH><TH>Visitors</TH></TR>"

	For i=1 to maxCPV

	  Response.write "<TR><TD align=center>" & i & "</TD><TD align=center>" & aClicksPerVisitor(i) & "</TR>"

    Next

    Response.Write "</TABLE></td></tr></table>"

  End Sub


  Sub repairClicksPerVisitor

    for i=1 to Ubound(aClicksPerVisitor) - 1

      aClicksPerVisitor(i) = aClicksPerVisitor(i) - aClicksPerVisitor(i+1)

    Next

  End Sub


%>


</BODY>

</HTML>