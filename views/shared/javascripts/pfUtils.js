/**
 *  @author Boris Searles (protofunc.com)
 */

function getFormattedTimeString(total_sec) {
	if (total_sec.toString().indexOf(":") == -1) {
	var timeString = "";
    var min = Math.floor(total_sec / 60);
	var hour = Math.floor(min / 60);
	var sec = Math.floor(total_sec - (min * 60));
	min = Math.floor(min - (hour * 60));
	
    if(min<10){min = "0" + min;}
    if(sec<10){sec = "0" + sec;}
	timeString = "";
	if(hour > 0) timeString += hour+":";
	else timeString += "0" + ":";
    timeString += min+":"+sec;
	}
	else {  timeString = "";
		timeString=total_sec;}
	return timeString;
}
getTimeString = function(sec)	{
		var timeString = "";
	    var min = Math.floor(sec / 60);
		var hour = Math.floor(min / 60);
		sec = Math.floor(sec - (min * 60));
		min = Math.floor(min - (hour * 60));

	    if(min<10){min = "0" + min;}
	    if(sec<10){sec = "0" + sec;}
		if(hour > 0) timeString = hour+":";
		else timeString = "0" + ":";
	    timeString += min+":"+sec;
		return timeString;
}

function splits(string,text) {
// splits string at text
    var strLength = string.length, txtLength = text.length;
    if ((strLength == 0) || (txtLength == 0)) return;

    var i = string.indexOf(text);
    if ((!i) && (text != string.substring(0,txtLength))) return;
    if (i == -1) {
        splitArray[splitIndex++] = string;
        return;
    }

    splitArray[splitIndex++] = string.substring(0,i);
    
    if (i+txtLength < strLength)
        splits(string.substring(i+txtLength,strLength),text);

    return;
}

function split(string,text) {
    splitIndex = 0;
    splits(string,text);
}

var splitIndex = 0;
var splitArray = new Array();

function calculateTime(ftime) {
	if (ftime.indexOf(":") != -1 ){
	var ntime=ftime.substring(0,7);
    splitIndex = 0;
    split(ntime,':');

    for (var i=splitIndex-1, j=1, answer=0; i>=0; i=i-1, j=j*60)
        answer += splitArray[i]*j - 0;
	}
	else {answer=ftime;}
	return answer;
}

function gup(name) {
    name = name.replace(/[\[]/, "\\\[ ").replace(/[\]] /, " \\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null)
    return ""; else
    return results[1];
}
