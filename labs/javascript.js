var animationSpeed = 100;

function loadAjaxData(aUrl, aParameters, aTargetId)
{
	$.ajax({ 
		url: aUrl,
		type: "POST",
		data: aParameters,
		dataType: "html",
		success: function(data, textStatus, XMLHttpRequest) 
		{
			$(aTargetId).html(data);
			$(aTargetId).slideDown(animationSpeed);
			$(aTargetId).siblings(".expander").removeClass("closed");
			$(aTargetId).siblings(".expander").removeClass("loading");
			$(aTargetId).siblings(".expander").addClass("open");
			bindEvents();
		},
		error: function(msg)
		{
			alert("Error: " + msg);
		}
	})
}

function loadChildren(aParentId, aTargetId)
{
	if ($(aTargetId).is(":visible"))
	{
		$(aTargetId).slideUp(animationSpeed);
		$(aTargetId).siblings(".expander").addClass("closed");
		$(aTargetId).siblings(".expander").removeClass("open");
	}
	else
	{
		if ($(aTargetId).children("li").length > 0)
		{
			$(aTargetId).slideDown(animationSpeed);
			$(aTargetId).siblings(".expander").addClass("open");
			$(aTargetId).siblings(".expander").removeClass("closed");
		}
		else
		{
			$(aTargetId).siblings(".expander").addClass("loading");
			loadAjaxData("listEntities.php", "parentId=" + aParentId, aTargetId);
		}
	}
}

function bindEvents()
{
	$(".expander").unbind("click").click(function () {
		var entityId = $(this).parent().attr("data-id");
		loadChildren(entityId, "#children" + entityId);
	});
	
	$(".nodeName").unbind("click").bind("click", function (event) {
		loadAjaxData("main.css", "type=apa", "#editDiv");
		$("#editDiv").fadeIn(animationSpeed);
	});
	
	$(".nodeName").unbind("contextmenu").bind("contextmenu", function (event) {
		event.preventDefault();
		hidePopups();
		
		var o = {
            left: event.pageX -20,
            top: event.pageY + 12
        };
        $("#editButtons").offset(o);
		$("#editButtons").fadeIn(animationSpeed);
	});
}

function hidePopups()
{
	$("#editButtons").fadeOut(animationSpeed);
	$("#editDiv").fadeOut(animationSpeed);
}

$(document).ready(function () {
	$("#content").load("listEntities.php");
	
	$("body").click(function () {
		hidePopups();
	});
});