function toggleWindow(idName,displayStyle) {

  //alert("function called");
  var target = document.getElementById(idName);
  //where target is the login window status
  if (target.style.display == "none") {
    target.style.display = displayStyle;
  } 
  else {
    target.style.display = "none";
  }
}

function changeFlexValue(idName,idDep,defaultVal,newVal)
{
	var dep = document.getElementById(idDep);
	var target = document.getElementById(idName);
	if(dep.style.display == "inline-block")
	{
		target.style.flex = newVal;
	}
	else
	{
		target.style.flex = defaultVal;
	}
}

function changeMarginValue(className,idDep,defaultVal,newVal)
{
	var dep = document.getElementById(idDep);
	//var target = document.getElementById(idName);
	var targets = document.getElementsByClassName(className);
	//alert(targets[0]);
	if(dep.style.display == "inline-block")
	{
		targets[0].style.marginTop = newVal + "ex";
	}
	else
	{
		targets[0].style.marginTop = defaultVal + "ex";
	}
}

function exclusiveToggleWindow(windowClass,windowId, displayStyle)
{
	var targets = document.getElementsByClassName(windowClass);
    for(var i = 0; i < targets.length; i++)
	{
        targets[i].style.display = "none"; 
    }
	
	toggleWindow(windowId,displayStyle);
	
	
	var info = document.getElementById('infoWindow');
	if(windowId!='infoWindow' && info.style.display != 'none' )
	{
		toggleWindow('infoWindow','block');
	}
	
}






