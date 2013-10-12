var mouseX 				= 0;
var mouseY 				= 0;
var newsIndex 			= 0;
var processing 			= false;
var safety 				= 0;
var fixed				= false;

jQuery(document).ready(function()
{
   $().mousemove(function(e)
   {
	  mouseX = e.pageX;
	  mouseY = e.pageY;
   }); 
})
			
function showHideDiv(aDivId)
{	
	var visibility = document.getElementById(aDivId).style.visibility;
	
	if (visibility == "hidden" || visibility == "")
	{
		showHide 	= "visible";
	}
	else
	{
		showHide 	= "hidden";
	}
	document.getElementById(aDivId).style.visibility  = showHide;
}

function openCloseDiv(aDivId)
{
	var display = document.getElementById(aDivId).style.display;

	if (display == "none" || display == "")
	{
		openClose 	= "block";
	}
	else
	{
		openClose 	= "none";
	}
	document.getElementById(aDivId).style.display  = openClose;
}

function closeDiv(aDivId)
{	
	document.getElementById(aDivId).style.display  = "none";
}

function setLock(aLockId)
{
	$("#" + aLockId).val("1"); 
}

function postComment(aContentId)
{
	$("#save_comment_" + aContentId).val("1"); 
	closeCommentForm();
	$("#commentform" + aContentId).submit();
}
function openCommentsForm(aContentId)
{
	closeCommentForm();
	$("#comments_" + aContentId).slideDown(200); 
	$("#visitor_name_" + aContentId).focus();
}

function closeCommentForm()
{
	$(".commentformdiv").slideUp(200); 
}
function positionDivAtCursor(aDivId, aOffsetX, aOffsetY)
{
	if (aOffsetX == null)
	{
		aOffsetX = 0;
	}
	if (aOffsetY == null)
	{
		aOffsetY = 0;
	}
	var myDiv 			= document.getElementById(aDivId);	
	myDiv.style.left 	= (mouseX + aOffsetX) + "px";
	myDiv.style.top 	= (mouseY + aOffsetY) + "px";
}

function deleteItem(aItemToDelete)
{
	if (confirm("Är du säker?"))
	{
		document.getElementById("user_action").value 	= "delete_item";
		document.getElementById("item_id").value 		= aItemToDelete;
		document.getElementById("inputform").submit();
	}
}

function deleteLink(aLinkToDelete)
	{
		if (confirm("Är du säker?"))
		{
			document.getElementById("user_action").value 	= "delete_link";
			document.getElementById("item_id").value 		= aLinkToDelete;
			document.getElementById("inputform").submit();
		}
	}

function addNewCategory()
{
	var newCategoryName = document.getElementById("new_category_name").value;
	document.getElementById("user_action").value 	= "add_new_category";	
	document.getElementById("inputform").submit();	
}

function deleteCategory(aCategoryId)
{
	document.getElementById("user_action").value 	= "delete_category";	
	document.getElementById("item_id").value 		= aCategoryId;
	document.getElementById("inputform").submit();	
}

function ajaxLoadNews(fillPage)
{	
	safety ++;
		
	if (newsIndex < $("#numberOfEntities").val())
	{
		$.ajax({
			url: "ajax.php",
			type: "POST",
			data: "index=" + newsIndex + "&selectedCategory=" + $("#selectedCategory").val() + "&searchString=" + $("#searchString").val(),
			dataType: "html",
			timeout:   10000,
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			beforeSend: function() {
				processing = true;
				$("#loadingIndicator").show();
			},
			success: function(data, textStatus, XMLHttpRequest) 
			{
				newsIndex ++;
				processing = false;
				
				$("#loadingIndicator").hide();
				$("#mainDiv > div").append(data);
				
				$("#mainDiv > div > div:last-child img").each(function() {
					$(this).wrap("<a href='" + $(this).attr("src") + "' rel='prettyPhoto[pageGallery]'>");
					$(this).attr("title", "Klicka på bilden för att se den i en större version.");
				});
				
				$("a[rel^='prettyPhoto']").prettyPhoto({social_tools: ""});
															
				if (fillPage && safety < 3)
				{
					ajaxLoadNews(true, searchString);
				}
				
				$("#indexIndicator").html(newsIndex + " av " + $("#numberOfEntities").val());
			},
			error: function(msg)
			{
				handleError(msg);
				cleanUpAjaxLoad();
			}
		});
		
		if ($("#searchString").val())
		{
			$("div:contains('" + $("#searchString").val() + "')").highlight($("#searchString").val(), "highlight");
		}
	}
}

$(window).scroll(function () 
{ 
   if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10 && !processing) 
   {
     ajaxLoadNews();
   }
});

jQuery.fn.highlight = function (str, className) {
    var regex = new RegExp(str, "gi");
    return this.each(function () {
        $(this).contents().filter(function() {
            return this.nodeType == 3 && regex.test(this.nodeValue);
        }).replaceWith(function() {
            return (this.nodeValue || "").replace(regex, function(match) {
                return "<span class=\"" + className + "\">" + match + "</span>";
            });
        });
    });
};

function scrollToTop()
{
	$("html, body").animate({
		scrollTop: 0
	}, 300);
}