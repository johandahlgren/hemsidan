$divToRefresh = "comments_" .  $_REQUEST["entityId"];

$result	= getEntities($_REQUEST["entityId"], "comment", null, "DESC", "ASC");
$numberOfComments = mysql_numrows($result);

if ($numberOfComments> 1)
{
	$extra = "er";
}
?>
<div class="commentsContainer">
	<div class="commentsHeadline">
		<?php
			if ($numberOfComments > 0)
			{
				?>
					<a class="showHideComments" onclick="$('#commentsList<?php print $_REQUEST["entityId"] ?>').slideToggle(200);">Visa <?php print $numberOfComments ?> kommentar<?php print $extra ?></a>
				<?php
			}
		?>
		<a class="commentButton" title="L&auml;mna en kommentar" onclick="$('.commentPopup').hide(); $('.commentButton').css('visibility', 'hidden'); openAdmin('<?php print getConfigProperty("cmsPath") ?>/formHandler.php?userAction=insert&amp;parentId=<?php print $_REQUEST["entityId"] ?>&amp;type=comment', 'commentDiv_<?php print $_REQUEST["entityId"] ?>', '<?php print $divToRefresh ?>', 'false');">Kommentera</a>
	</div>
	<div id="commentDiv_<?php print $_REQUEST["entityId"] ?>" class="commentPopup"></div>
	<div class="commentsInnerContainer">
		<div id="commentsList<?php print $_REQUEST["entityId"] ?>" class="commentsDiv" style="display: none;">
			<?php
				while (list ($id, $name, $type, $parentId, $publishDate, $sortOrder, $data) = mysql_fetch_row ($result))
				{
					?>
						<div class="commentRow">
							<div class="commentName"><?php print getValueFromString("visitorName", $data); ?></div>
							<div class="commentDate"><?php print ucfirst(getYMDHShort($publishDate)) ?></div>
							<div class="commentText"><?php print getValueFromString("commentText", $data); ?></div>
						</div>
					<?php
				}
			?>
			<div onclick="$('#commentsList<?php print $_REQUEST["entityId"] ?>').slideToggle(200);" class="closeComment" title="D&ouml;lj kommentarer"></div>
		</div>
	</div>
</div>
	
<script type="text/javascript">
	$("#<?php print $divToRefresh ?>").bind("update", function(){
		loadAjaxData("comments_<?php print $_REQUEST["entityId"] ?>", "userAction=loadComponent&componentName=comments&entityId=<?php print $_REQUEST["entityId"] ?>");
		return false;
	});
</script>

