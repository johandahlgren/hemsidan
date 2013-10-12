<?php
	import_request_variables("GPC", "");
	ob_start("ob_gzhandler");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta name="description" content="A dice roll simulator! It allows you to enter the type of dice, number of dice to roll and the number of times to roll the dice. The results are displayed as a nice bar chart. Perfect for RPG:ers and budding scientists everywhere, interested in the rolling of dice. Use it for fun, for a school project or whatever." />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<script type="text/javascript" src="http://www.google-analytics.com/ga.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<title>Dice Roll PRO</title>
		<style type="text/css" media="print">
		
		</style>
		<style type="text/css"> 
			* {font-family: arial; font-size: 14px; }
			body {padding: 0px; margin: 0px; background-color: rgb(50, 50, 50);}
			body.phone {width: 320px; background-image: none; background-color: rgb(50, 50, 50);}
			body.phone * {text-shadow: none !important; background-image: none !important; color: rgb(220, 220, 220) !important; border-radius: 0px !important; -moz-border-radius: 0px !important; -webkit-border-radius: 0px !important; -webkit-box-shadow: none !important; -moz-box-shadow: none !important;}
			body.phone .die {}
			body.phone .button {background-color: rgb(100, 100, 100); border-style: solid !important; }
			body.phone .bar {}
			body.phone .inputfield {text-shadow: none; -moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none; color: black !important;}
			h1 {display: none;}
			h2 {color: rgb(230, 210, 100); font-size: 16px; margin: 10px 0px 10px 0px; text-align: center; text-shadow: 0px 0px 8px rgba(230, 200, 0, 0.8);}
			p {margin: 0px 0px 10px 0px;}
			form, fieldset {border: none; margin: 0px; padding: 0px;}
			legend {display: none;}
			thead {display: none;}
			input, select {width: 95px; height: 42px; color: rgb(120, 70, 0); font-size: 25px; line-height: 38px; text-shadow: 0px 0px 15px rgba(240, 220, 100, 0.9); margin-right: 5px; outline: none; text-align: center; border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px;}
			input[type="submit"] {display: none;}
			select.gradientField {background: #B9E258; font-size: 16px; height: 40px;}
			#viewWindow {position: absolute; display: block; width: 320px; height: 505px; overflow: hidden;}
			#container {position: absolute; left: -320px; width: 1000px; height: 450px; display: block; }
			#tabHeader {background-color: rgb(100, 100, 100); border-bottom: 1px solid rgb(50, 50, 50); width: 100%;}
			#tab_about {margin-right: 0px;}
			#selectionDiv {} 
			#dieRollRpgDiv {margin-bottom: 10px; height: 120px; border-radius: 15px; -moz-border-radius: 15px; -webkit-border-radius: 15px;}
			#scientificContainer {margin-top: 20px; height: 180px;}
			#dieRollScientificDiv {display: none; overflow: auto; padding: 7px;}
			#diagramDiv {display: table; margin-left: auto; margin-right: auto;}
			#previousNextButtons {position: absolute; width: 320px; height: 40px; margin-top: -125px;}
			#dice {margin-left: 9px; margin-right: 9px; width: 156px; float: left;}
			#diceName {margin-top: 0px; position: absolute; font-size: 18px; color: white; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);}
			#dieRollResult {font-size: 90px; line-height: 90px; text-align: center; font-weight: bold; color: rgb(240, 240, 240); text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);}
			#copyrightDiv {text-align: center; color: rgb(80, 80, 80); clear: both; margin-top: 30px;}
			#copyrightDiv a {color: rgb(150, 150, 150); text-decoration: underline;}
			#loadingDiv {width: 220px; height: 20px; margin: 100px auto; background-image: url("loading.gif"); background-repeat: no-repeat;}
			#displayRolls {text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.8); font-weight: normal; font-size: 12px; line-height: 18px;}
			#displayRolls span {text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.8); font-weight: normal; font-size: 24px; line-height: 18px;}
			#operatorButtons {clear: both;}
			#operator_x {float: right;}
			#about {color: rgb(220, 220, 220); padding: 10px; text-align: justify;}
			#scrollButtons {display: block; width: 320px; height: 48px; position: fixed; bottom: 0px; left: 0px; background-color: rgb(100, 100, 100); border-top: 1px solid rgb(50, 50, 50); background: #B2B2B2; /* old browsers */ background: -moz-linear-gradient(top, #B2B2B2 0%, #777777 50%, #686868 53%, #2D2D2D 100%); /* firefox */ background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#B2B2B2), color-stop(50%,#777777), color-stop(53%,#686868), color-stop(100%,#2D2D2D)); /* webkit */ filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#B2B2B2', endColorstr='#2D2D2D',GradientType=0 ); /* ie */}
			#calculating {display: none; clear: both; width: 48px; height: 48px; position: absolute; margin: 20px; }
			#displayRolls span.rolledMinimum {color: rgb(255, 120, 120); text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.8);}
			#displayRolls span.rolledMaximum {color: rgb(120, 255, 120); text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.8);}
			.diagramInfo {color: white; margin: 35px auto; line-height: 24px; text-align: center; width: 122px; padding: 10px; text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);}
			.infoDiv {display: none; height: 330px; width: 270px; position: absolute; top: 0px; left: 0px; background-color: rgb(240, 240, 240); border: 3px solid rgb(150, 150, 150); padding: 20px; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; z-index: 20000; -webkit-box-shadow: 5px 5px 25px rgba(100, 100, 100, 0.75); -moz-box-shadow: 5px 5px 25px rgba(100, 100, 100, 0.75);}
			.tab {margin-right: 60px; line-height: 30px; color: rgb(220, 220, 220); text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.5); cursor: pointer; float: left; min-width: 60px; text-align: center;}
			.tabdiv {z-index: 100; width: 320px; margin: 0px auto 0px auto; float: left;}
			.gradientField {border: 1px solid white; -moz-box-shadow:inset 0px 0px 5px rgba(0, 0, 0, 0.8); -webkit-box-shadow:inset 0px 0px 5px rgba(0, 0, 0, 0.8); box-shadow:inset 0px 0px 5px rgba(0, 0, 0, 0.8); background: #B9E258; /* old browsers */ background: -moz-linear-gradient(top, #B9E258 0%, #A6CC35 50%, #8DAD2D 100%); /* firefox */ background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#B9E258), color-stop(50%,#A6CC35), color-stop(100%,#8DAD2D)); /* webkit */ filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#B9E258', endColorstr='#8DAD2D',GradientType=0 ); /* ie */}}
			.panel {padding: 10px;} 
			.resultTable {border-collapse: collapse;}
			.resultTable td {padding: 0px; margin: 0px;}
			.resultTd {line-height: 10px; font-size: 10px; text-align: center; vertical-align:text-top; border-top: 1px solid rgb(120, 120, 120); padding: 5px 1px 5px 1px;} 
			.bar {border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; margin-bottom: 5px; -webkit-box-shadow: inset 0px 0px 1px rgba(0, 0, 0, 0.9); -moz-box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.9); background-color: rgb(0, 255, 0); background-image: -webkit-gradient(linear, left bottom, left top, color-stop(0.04, rgb(250,32,3)), color-stop(0.5, rgb(255,242,0)), color-stop(1, rgb(21,255,0))); background-image: -moz-linear-gradient(center bottom, rgb(250,32,3) 4%, rgb(255,242,0) 50%, rgb(21,255,0) 100%);}
			.average {margin-top: 10px; font-size: 10px; line-height: 14px; color: rgb(200, 200, 200);}
			.error {background-color: red;}
			.footer {clear: both;}
			.selected {cursor: auto; z-index: 1000; background: none; text-shadow: 0px 0px 10px rgb(255, 200, 0); color: white; font-weight: bold;}
			.middledivider {font-size: 10px; line-height: 10px; color: rgb(255, 255, 0);}
			.button {border: 2px outset rgb(100, 100, 100); background-color: rgb(70, 70, 70); font-size: 20px; color: rgb(220, 220, 220); width: 64px; height: 64px; display: block; float: left; line-height: 64px; text-align: center; text-decoration: none; margin: 0px 10px 10px 0px; cursor: pointer; color: rgb(220, 220, 220); border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.8); background-color: rgb(100, 100, 100); background: #B2B2B2; /* old browsers */ background: -moz-linear-gradient(top, #B2B2B2 0%, #777777 50%, #686868 53%, #2D2D2D 100%); /* firefox */ background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#B2B2B2), color-stop(50%,#777777), color-stop(53%,#686868), color-stop(100%,#2D2D2D)); /* webkit */ filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#B2B2B2', endColorstr='#2D2D2D',GradientType=0 ); /* ie */}		
			.button:active {border: 2px inset rgb(80, 80, 80); -moz-box-shadow:inset 2px 2px 5px rgba(0, 0, 0, 0.8); -webkit-box-shadow:inset 2px 2px 5px rgba(0, 0, 0, 0.8); box-shadow:inset 2px 2px 5px rgba(0, 0, 0, 0.8); }
			.mainButton {width: 310px; margin-top: 10px;}
			.rpgButton {width: 232px; margin-right: 0px; margin-top: 0px;}
			.additionButton {height: 40px; line-height: 40px; margin-left: 0px; margin-right: 0px;}
			.operatorButton {width: 24px; text-align: center; float: left; text-decoration: none; margin: 0px 10px 0px 10px; line-height: 24px;}
			.resetButton {}
			.scrollButton {background: none; background-color: rgba(0, 0, 0, 0.2); border-style: inset; border-width: 1px; height: 35px; line-height: 30px; width: 120px; margin: 5px 10px 5px 10px;}
			.die {color: rgb(220, 220, 220); margin-bottom: 15px; text-align: center; text-shadow: none; font-size: 14px; width: 67px; display: block; float: left;}
			.die text {float: left;}
			.die img {width: 24px;}
			.dieName {margin-top: -48px;}
			.selectedButton {color: white; font-weight: bold; text-shadow: 0px 0px 20px rgba(255, 255, 0, 0.8);}
			.instant {margin-right: 0px;}
			.mods {color: rgb(255, 255, 0);}
			.instantX {float: right; margin-top: -40px; margin-right: 10px; font-size: 32px; color: white; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);}
			.center {margin-left: auto; margin-right: auto; display: table;}
			.left {float: left;}
			.right {float: right;}  
			.prevNext {display: none; color: #8AAA2A; text-decoration: none;}
			.prevNextArrow{width: 60px; text-align: center; font-size: 40px; line-height: 20px; font-weight: bold;}
			.prevNextDice {width: 60px; text-align: center; font-size: 12px;margin-top: 5px; clear: both;}
			.prevNextResult {width: 60px; text-align: center; font-size: 30px; font-weight: bold; clear: both;}
			.fieldName {color: rgb(220, 220, 220);}
		</style>
		<script type="text/javascript">
			<!--
			var viewNumber 		= 1;
			var previousRolls 	= new Array();
			var currentIndex 	= -1;

			$(document).ready(function() 
			{
				$(".die").bind("contextmenu",function(e){
					return false;
				});
				
				$(".die").mousedown(function(e){
					doSomething(e);
				});
				
				$("#previousButton").bind("click",function(e){previousRoll()});
				$("#nextButton").bind("click",function(e){nextRoll()});
				$("#subtractModifier").bind("click",function(e){addModifier(-1)});
				$("#addMofifier").bind("click",function(e){addModifier(1)});
				$("#reset").bind("click",function(e){reset()});
				$("#rollRpg").bind("click",function(e){rollRpgDice()});
				$("#rollScientific").bind("click",function(e){rollScientificDice(null)});
				$("#scrollLeft").bind("click",function(e){scrollDiv(-1);});
				$("#scrollRight").bind("click",function(e){scrollDiv(1)});
				
				$("#operator").bind("change",function(e){setOperator(this.value)});
				$("#operator").bind("focus",function(e){clearError('numberOfSides')});
				$("#numberOfDice").bind("focus",function(e){clearError('numberOfDice')});
				$("#numberOfSides").bind("focus",function(e){clearError('numberOfSides')});
				$("#modifier").bind("focus",function(e){clearError('modifier')});
				$("#numberOfRolls").bind("focus",function(e){clearError('numberOfRolls')});
				$("#display").bind("focus",function(e){clearError('display')});
				$("#dice").bind("focus",function(e){clearError('dice')});
				
				$("#rpgForm").bind("submit",function(e){rollRpgDice(); return false;});
				$("#scientificForm").bind("submit",function(e){rollScientificDice(); return false;});
				
				resetResult("Awaiting roll...");
				var dice = $("#dice").val();
				parseDice(dice);
			});
			
			function scrollDiv(aAmount)
			{
				viewNumber += aAmount;
				$("#container").animate({"left": "-=" + (aAmount * 320) + "px"}, 300);
				
				if (viewNumber == 0)
				{
					$("#scrollLeft").fadeOut(200);
					$("#scrollRight").fadeIn(200);
					
					$("#scrollRight").html("Roll");
				}
				else if (viewNumber == 1)
				{
					$("#scrollLeft").fadeIn(200);
					$("#scrollRight").fadeIn(200);
					
					$("#scrollLeft").html("Help");
					$("#scrollRight").html("Stat");
				}
				else if (viewNumber == 2)
				{
					$("#scrollLeft").fadeIn(200);
					$("#scrollRight").fadeOut(200);
					
					$("#scrollLeft").html("Roll");
				}
			} 
			
			function roundNumber(number, decimals) 
			{
				var newnumber = new Number(number+'').toFixed(parseInt(decimals));
				return parseFloat(newnumber);
			}
			
			function displayTab(aTabId)
			{
				$(".tab").removeClass("selected");
				$("#tab_" + aTabId).addClass("selected");
				$(".tabdiv").css("display", "none");
				$("#tabdiv_" + aTabId).css("display", "block");

				if (aTabId == "rpg")
				{
					$("#helpRpg").show();
					$("#helpScientific").hide();
				}
				else
				{
					$("#helpRpg").hide();
					$("#helpScientific").show();
				}
			}
			function displayDiceName()
			{
				var numberOfDice	= parseInt($("#numberOfDice").val());
				var numberOfSides	= parseInt($("#numberOfSides").val());
				var operator		= $("#operator").val();
				var modifier		= parseInt($("#modifier").val());
				var numberOfRolls	= parseInt($("#numberOfRolls").val());
				var modifierSign	= "";
				var mod				= "";
				
				if (parseInt(modifier) > 0)
				{
					modifierSign = "+";
				}
				
				if (modifier != 0)
				{
					mod = modifierSign + modifier;
				}
				
				$("#dice").val(numberOfDice + operator + numberOfSides + mod);
			}
			function resetResult(aString) 
			{
				var defaultString = "<div class=\"diagramInfo\">" + aString + "</div>";
				$("#dieRollResult").html(defaultString);
				$("#dieRollScientificDiv").html(defaultString);
			}
			function clearError(aFieldId)
			{
				$("#" + aFieldId).removeClass("error");
			}
			function validateNumber(aFieldToCheck)
			{
				valueToCheck = $("#" + aFieldToCheck).val();
				
				if (valueToCheck == "" || valueToCheck == " " || isNaN(valueToCheck))
				{
					$("#" + aFieldToCheck).addClass("error");
					$("#dice").addClass("error");
					return false;
				}
				else
				{
					return true;
				}
			}
			
			function doSomething(e) 
			{
				var rightClick;
				
				if (!e) 
				{
					var e = window.event;
				}
				
				if (e)
				{
					if (e.which) 
					{
						rightClick = (e.which == 3);
					}
					else if (e.button) 
					{
						rightClick = (e.button == 2);
					}
					
					if (e.target) 
					{
						targ = e.target;
					}
					else if (e.srcElement) 
					{
						targ = e.srcElement;
					}
					
					if (targ.nodeType == 3 || targ.nodeName == "IMG" || targ.id.indexOf("text_") > -1)
					{
						targ = targ.parentNode;
					}
					
					if (targ.id == "d100")
					{
						$('#dice').val('1d100');
						setOperator('d'); 
						resetDieSelection();
						rollRpgDice();
					}
					else if (targ.id == "x10")
					{
						$('#dice').val('2x10'); 
						setOperator('x'); 
						resetDieSelection(); 
						rollRpgDice();
					}
					else
					{
						setOperator('d'); 
						resetDieSelection();
						$("#" + targ.id).addClass("selectedButton");
						
						clickedDie = targ.id.substring(1);
						
						if (!rightClick)
						{
							addDie(clickedDie);
						}
						else
						{
							removeDie(clickedDie);
						}
					}
				}
				else
				{
					alert("Event not found...");
				}
			}
			
			function resetDieSelection()
			{
				$(".die").removeClass("selectedButton");
				$("#dieRollScientificDiv").fadeOut(0);
			}
			
			function addDie(aDieType)
			{
				if ($("#numberOfSides").val() == aDieType)
				{
					$("#numberOfDice").val(parseInt($("#numberOfDice").val()) + 1);
				}
				else
				{
					$("#numberOfDice").val(1);
				}
				$("#modifier").val(0);
				$("#numberOfSides").val(aDieType);
				
				displayDiceName();
			}
			
			function removeDie(aDieType)
			{
				if ($("#numberOfSides").val() == aDieType)
				{
					$("#numberOfDice").val(parseInt($("#numberOfDice").val()) - 1);
				}
				
				if ($("#numberOfDice").val() < 1)
				{
					$("#numberOfDice").val(1);
				}
				$("#numberOfSides").val(aDieType);
				
				displayDiceName();
			}
			
			function parseDice(aDice)
			{
				if(aDice.indexOf("d") > 0)
				{
					operator = "d";
				}
				else if(aDice.indexOf("x") > 0)
				{
					operator = "x";
				}
				
				var numberOfDice = aDice.substring(0, aDice.indexOf(operator));
				var temp = aDice.substring(aDice.indexOf(operator) + 1);
				var numberOfSides = 0;
				var modifier	= 0;
				if (temp.indexOf("+") > -1)
				{
					numberOfSides = temp.substring(0, temp.indexOf("+"));
					modifier = temp.substring(temp.indexOf("+"));
				}
				else if (temp.indexOf("-") > -1)
				{
					numberOfSides = temp.substring(0, temp.indexOf("-"));
					modifier = temp.substring(temp.indexOf("-"));
				}
				else
				{
					numberOfSides = temp;
				}
				
				$("#numberOfDice").val(parseInt(numberOfDice));
				$("#numberOfSides").val(parseInt(numberOfSides));
				$("#modifier").val(parseInt(modifier));
				$("#operator").val(operator);
				//$("#numberOfRolls").val(parseInt(1));
			}
			
			function rollRpgDice()
			{
				var operator = "";
				var dice = $("#dice").val();
				parseDice(dice);
				$("#dieRollScientificDiv").fadeOut(0);
				performRoll(1);
			}
			
			function rollScientificDice()
			{
				$("#calculating").fadeIn(200, function() {performRoll($("#numberOfRolls").val())});
			}
			
			function performRoll(aNumberOfRolls)
			{
				var ok1 = validateNumber("numberOfDice");
				var ok2 = validateNumber("numberOfSides");
				var ok3 = validateNumber("modifier");
				var ok4 = validateNumber("numberOfRolls");
				
				if (!ok1 || !ok2 || !ok3 || !ok4)
				{
					resetResult("Invalid input!");
					_gaq.push(['_trackEvent', 'Dice roll', 'Failure', 'The user tried to roll ' + numberOfDice + 'd' + numberOfSides + ' ' + numberOfRolls + ' times.']);
					return false;
				}
				
				var total 			= 0;
				var rolledDie		= 0;
				var numberOfDice	= parseInt($("#numberOfDice").val());
				var numberOfSides	= parseInt($("#numberOfSides").val());
				var operator		= $("#operator").val();
				var modifier		= parseInt($("#modifier").val());
				var numberOfRolls	= parseInt($("#numberOfRolls").val());
				var rolledTotal		= 0;
				var average			= 0;
				var resultArray		= new Array(); 
				var alertString		= "";
				var alertStringBars	= "";
				var alertStringNumbers = "";
				var maxValue		= 0;
				var batchSize 		= 1;
				
				if (aNumberOfRolls != null)
				{
					numberOfRolls = aNumberOfRolls;
				}
								
				if ($("#display").val() == "batch")
				{
					batchSize 		= numberOfSides;
				}
				
				if (isNaN(modifier))
				{
					modifier = 0; 
				}

				var previousValue = 0;
				
				if (numberOfRolls > 1)
				{
					for (i = 0; i < numberOfRolls; i ++)
					{
						rolledTotal 	= rollDice(numberOfDice, numberOfSides, operator, modifier, false)[0];
						total 			= total + rolledTotal;
						
						if (batchSize > 1)
						{
							rolledTotal = Math.round(rolledTotal / batchSize)
						}
						
						previousValue 	= resultArray["x" + rolledTotal];
						
						if (previousValue == null || previousValue == "")
						{
							previousValue = 0;
						}
						
						resultArray["x" + rolledTotal] = previousValue + 1;
						
						if (resultArray["x" + rolledTotal] > maxValue)
						{
							maxValue = resultArray["x" + rolledTotal]; 
						}
					}
					
					modifierString = "";
					
					if (modifier > 0)
					{
						modifierString = "+" + modifier; 
					}
					else if (modifier < 0)
					{
						modifierString = modifier;
					}
				
					alertString = "<div id=\"diagramDiv\">";
					alertString += "<div id=\"diceName\">" + numberOfDice + operator + numberOfSides + modifierString + "</div>";
					alertString += "<table class=\"resultTable\">";
					var imageWidth = 0;

					alertString = alertString + "<tr>";

					var start 		= 0;
					var end 		= 9999;
					
					if (operator == "d")
					{
						start 	= numberOfDice + modifier;
						end 	= numberOfDice * numberOfSides + modifier + 1;
					}
					else
					{
						start 	= 1 + modifier;
						end 	= Math.pow(numberOfSides, numberOfDice) + modifier + 1;
					}
					
					if (batchSize > 1)
					{
						end = Math.ceil(end / batchSize);
					}
					
					if (end > 500)
					{
						alert("Too many results to show. Aborting at 500.");
						end = 500;
					}
					
					var imageWidth = Math.round(320 / end);
					
					if (imageWidth < 1)
					{
						imageWidth = 1;
					}
					
					var middleValue 	= 0;
					var addition		= 0;
					var foundMiddle 	= false;
					var thisValue		= 0;
					var currentValue	= 0;
					var displayValue	= 0;
					
					for (x = start; x < end; x ++)
					{
						currentValue 	= 0;
						currentValue 	= resultArray["x" + x];
						imageHeight 	= (currentValue / maxValue) * 130;
						var temp 		= "" + x;
						var value 		= "";
						
						addition += currentValue;
						
						var tdClass = "resultTd";
						
						if (addition >= Math.round(numberOfRolls / 2) && !foundMiddle)
						{
							middleValue = x;
							foundMiddle = true;
							tdClass = "middledivider resultTd";
						}
						
						if (batchSize > 1)
						{
							displayValue = temp * batchSize;
						}
						else
						{
							displayValue = temp;
						}
						
						if (temp.length == 1)
						{
							value = temp;
						}
						else
						{
							for (i = 0; i < temp.length; i ++)
							{
								value = value + temp.charAt(i) + "<br/>";
							}
						}
						
						var percentage = roundNumber(((currentValue / numberOfRolls) * 100), 3);
						
						alertStringBars		= alertStringBars + "<td valign=\"bottom\"><div class=\"bar\" style=\"width: " + imageWidth + "px; height: " + imageHeight + "px;\" title=\"" + x + ": " + currentValue + " rolls, " + percentage + "%\"></td>";
						alertStringNumbers 	= alertStringNumbers + "<td title=\"" + currentValue + " rolls, " + percentage + "%\" class=\"" + tdClass + " fieldName\">" + displayValue + "</td>";
					}

					alertString += "<tr>" + alertStringBars + "</tr>"; 
					if (end <= 31)
					{
						alertString += "<tr>" + alertStringNumbers + "</tr>";
					}
					
					average = Math.round(total / numberOfRolls);
					
					if (batchSize > 1)
					{
						middleValue = middleValue * batchSize;
					}
						
					alertString += "<tr><td colspan=\"" + (end - start + 1) + "\"><div class=\"average center\">Average: " + average + ", Median: <span class=\"middleDivider\">" + middleValue + "</span></div></td></tr>"; 
					alertString += "</table>";
					alertString += "</div>";

					$("#dieRollScientificDiv").html(alertString);
					$("#dieRollScientificDiv").fadeIn(200);
				}
				
				$("#dieRollResult").fadeOut(100, function() {
					var result = rollDice(numberOfDice, numberOfSides, operator, modifier, true);
					if (numberOfDice > 1)
					{
						$("#dieRollResult").html(result[0] + "<div id=\"displayRolls\">" + result[1] + "</div>");
					}
					else
					{
						$("#dieRollResult").html(result[0]);
					}
					
					addHistory();
					currentIndex = previousRolls.length - 1;
					displayPreviousNextButtons();
					
					$("#calculating").fadeOut(200);
					
					_gaq.push(['_trackEvent', 'Dice roll', 'Success', 'The user rolled ' + numberOfDice + 'd' + numberOfSides + ' ' + numberOfRolls + ' times.']);
						
					$("#dieRollResult").fadeIn(100);
				});	
			}
			function rollDice(aNumberOfDice, aNumberOfSides, aOperator, aModifier, aIsSingleRoll)
			{
				var rolledTotal 	= 0;
				var rolledValues 	= "";
				
				if (aOperator == "d")
				{
					rolledTotal = 0;
				}
				else
				{
					rolledTotal = 1;
				}
				
				for (x = 0; x < aNumberOfDice; x ++)
				{
					rolledDie = Math.round(Math.random() * aNumberOfSides) % aNumberOfSides + 1;
					
					if (aOperator == "d")
					{
						rolledTotal += rolledDie; 
					}
					else
					{
						rolledTotal = rolledTotal * rolledDie;
					}
					
					var extra = "";
					
					if (rolledDie == 1)
					{
						extra = "span class=\"rolledMinimum\" title=\"Minimum for this type of die (1).\"";
					}
					else if (rolledDie == aNumberOfSides)
					{
						extra = "span class=\"rolledMaximum\" title=\"Maximum for this type of die (" + rolledDie + ").\"";
					}
					
					if (aOperator == "d")
					{
						divider = " + ";
					}
					else
					{
						divider = " x ";
					}
					
					rolledValues += divider + "<span " + extra + ">" + rolledDie + "</span>";
				}
				
				var modsString = "";
					
				if (aModifier > 0)
				{
					modsString = "<span class=\"mods\"> + " + aModifier + "</div>";
				}
				else if (aModifier < 0)
				{
					modsString = "<span class=\"mods\"> - " + Math.abs(aModifier) + "</div>";
				}

				rolledValues += modsString;
				
				rolledValues = rolledValues.substring(2);
				
				if (aIsSingleRoll)
				{
					$("#dieRollValue").val(rolledTotal + aModifier);
					$("#dieRollValues").val(rolledValues);
				}
				
				return new Array(rolledTotal + aModifier, rolledValues);
			}
			
			function toggleHelpDialog(aDivId)
			{
				$("#" + aDivId).fadeToggle(200);
			}
			
			function addModifier(aModifier)
			{
				var modifier 	= parseInt(parseInt($("#modifier").val()));
				modifier 		= modifier + aModifier;
				$("#modifier").val(modifier);
				displayDiceName();
			}
			
			function setOperator(aOperator)
			{
				$(".operatorButton").removeClass("selectedButton");
				$("#operator_" + aOperator).addClass("selectedButton");
				$("#operator").val(aOperator);
			}
			
			function reset()
			{
				$("#numberOfDice").val(1);
				$("#modifier").val(0);
				displayDiceName();
				resetResult("Selection reset");
			}
			
			function previousRoll()
			{
				currentIndex -= 1;
				$("#dice").val(previousRolls[currentIndex][0]);
				parseDice(previousRolls[currentIndex][0]);
				displayPreviousNextButtons();
				displayPreviousRoll();	
			}
			
			function nextRoll()
			{
				currentIndex += 1;
				$("#dice").val(previousRolls[currentIndex][0]);
				parseDice(previousRolls[currentIndex][0]);
				displayPreviousNextButtons();
				displayPreviousRoll();
			}
			
			function displayPreviousRoll()
			{
				$("#dieRollResult").fadeOut(100, function() {
					$("#dieRollResult").html(previousRolls[currentIndex][1] + "<div id=\"displayRolls\">" + previousRolls[currentIndex][2] + "</div>");
					$("#dieRollResult").fadeIn(100);
				});
			}
			
			function addHistory()
			{
				var newValue 		= new Array();
				newValue[0] 		= $("#dice").val();
				newValue[1] 		= $("#dieRollValue").val();
				newValue[2] 		= $("#dieRollValues").val();
				
				previousRolls[previousRolls.length] 	= newValue;
				
				currentIndex += 1;
			}
			
			function displayPreviousNextButtons()
			{
				if (currentIndex > 0)
				{
					$("#previousButton").fadeIn(200);
					$("#previousButton").html("<div class=\"prevNextArrow left\">&larr;</div> <div class=\"prevNextDice left\">" + previousRolls[currentIndex - 1][0] + "</div><div class=\"prevNextResult left\">" + previousRolls[currentIndex - 1][1] + "</div>");
				}
				else 
				{
					$("#previousButton").fadeOut(200); 
				}
				
				if (currentIndex < previousRolls.length - 1)
				{
					$("#nextButton").fadeIn(200);
					$("#nextButton").html("<div class=\"prevNextArrow right\">&rarr;</div> <div class=\"prevNextDice right\">" + previousRolls[currentIndex + 1][0] + "</div><div class=\"prevNextResult right\">" + previousRolls[currentIndex + 1][1] + "</div>");
				}
				else
				{
					$("#nextButton").fadeOut(200);
				}
			}
			-->
		</script>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-1571967-7']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>
	<body class="<?php print $_REQUEST["class"] ?>">
		<h1>Dice Roll PRO</h1> 
		<div id="viewWindow">
			<div id="container">
				<div id="tabdiv_about" class="tabdiv">
					<div id="about" class="panel">
						<h2>Instructions:</h2>
						<p>Add/remove dice by left/right clicking on each die.</p>
						<p>Add/remove modifers to the roll by using the +1/-1 buttons next to the dice-field.</p>
						<p>You may also enter the number and type of dice and any modifiers directly into the text field in standard RPG syntax (i.e. 4d6+3).</p>
						<p><strong>Note1:</strong> You may also multiply the dice by using, for instance, 2x10.</p>
						<p><strong>Note2:</strong> The d100 and 2x10 buttons roll the dice immediately, since you seldom need to add two or more d100 rolls.</p>
						<p><strong>Note3:</strong> In the scientific display the Display property decides if every value will be shown in the diagram, or if they will be grouped.</p>
						<div id="copyrightDiv">&copy; Copyright <a href="http://www.dahlgren.st/johan">Johan Dahlgren</a> 2011</div>
					</div>
				</div>
				<div id="tabdiv_rpg" class="tabdiv">
					<div id="selectionDivRpg" class="panel">
						<form id="rpgForm" method="post" action="">
							<fieldset>
								<legend>RPG Form</legend>
								<input type="hidden" id="dieRollValue" value="0" />
								<input type="hidden" id="dieRollValues" value="0" />
									
								<div id="dieRollRpgDiv" class="gradientField">
									<div id="dieRollResult"></div>
								</div>
								<div id="previousNextButtons">
									<div id="previousButton" class="link prevNext left" title="Go to previous roll in history">&lt;</div>
									<div id="nextButton" class="link prevNext right" title="Go to next roll in the history.">&gt;</div>
								</div>
								<table>
									<thead>
										<tr>
											<th>
												RPG Table
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div id="subtractModifier" class="link additionButton button" title="Subtract one from the modifier.">-1</div>
												<label for="dice"></label>
												<input type="text" id="dice" value="3d6" class="inputfield gradientField" />
												<div id="addMofifier" class="additionButton button" title="Add 1 to the modifier.">+1</div>
											</td>
										</tr>
										<tr>
											<td>
												<div id="d4" class="button die" title="LMB: Add 1d4, RMB: Remove 1d4">
													<img src="images/d4.png" alt="d4"/>
													<div id="text_d4" class="dieName">d4</div>
												</div>
												<div id="d6" class="button die selectedButton" title="LMB: Add 1d6, RMB: Remove 1d6">
													<img src="images/d6.png" alt="d6"/>
													<div id="text_d6" class="dieName">d6</div>
												</div>
												<div id="d8" class="button die" title="LMB: Add 1d8, RMB: Remove 1d8">
													<img src="images/d8.png" alt="d8"/>
													<div id="text_d8" class="dieName">d8</div>
												</div>
												<div id="d100" class="button die instant" title="Roll 1d100">
													<img src="images/d100.png" alt="d100"/>
													<div id="text_d100" class="dieName">d100</div>
												</div>
												<div id="d10" class="button die" title="LMB: Add 1d10, RMB: Remove 1d10">
													<img src="images/d10.png" alt="d10"/>
													<div id="text_d10" class="dieName">d10</div>
												</div>
												<div id="d12" class="button die" title="LMB: Add 1d12, RMB: Remove 1d12">
													<img src="images/d12.png" alt="d12"/>
													<div id="text_d12" class="dieName">d12</div>
												</div>
												<div id="d20" class="button die" title="LMB: Add 1d20, RMB: Remove 1d20">
													<img src="images/d20.png" alt="d20"/>
													<div id="text_d20" class="dieName">d20</div>
												</div>
												<div id="x10" class="button die instant" title="Roll 2x10">
													<img src="images/d100.png" alt="2x10"/>
													<div id="text_x10" class="dieName">2x10</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div id="reset" class="link button resetButton" title="Reset the dice to 1dX, no mods.">C</div>
												<div id="rollRpg" class="link button mainButton rpgButton" title="Roll the dice.">Roll</div>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" value="Send" />
							</fieldset>
						</form>
					</div>
				</div>
				<div id="tabdiv_scientific" class="tabdiv">
					<div id="scientificContainer">
						<div id="dieRollScientificDiv" class="panel">
						</div>
					</div>
					<div id="selectionDivScientific" class="panel">
						<form id="scientificForm" method="post" action="">
							<fieldset>
								<legend>Scientific Form</legend>
								<table>
									<thead>
										<tr>
											<th>
												Scientific Table
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="fieldName">
												<label for="numberOfDice"># of dice</label>
											</td>
											<td class="fieldName">
												<label for="operator">Operator</label>
											</td>
											<td class="fieldName">
												<label for="numberOfSides"># of sides</label>
											</td>
										</tr>
										<tr>
											<td>
												<input type="text" id="numberOfDice" value="0" class="inputfield gradientField" />
											</td>
											<td>
												<select id="operator" class="inputfield gradientField">
													<option value="d">Add</option>
													<option value="x">Multiply</option>
												</select>
											</td>
											<td>
												<input type="text" id="numberOfSides" value="0" class="inputfield gradientField" />
											</td>
										</tr>
										<tr>
											<td class="fieldName">
												<label for="modifier">Modifier</label>
											</td>
											<td class="fieldName">
												<label for="numberOfRolls"># of rolls</label>
											</td>
											<td class="fieldName">
												<label for="display">Display</label>
											</td>
										</tr>
										<tr>
											<td>
												<input type="text" id="modifier" value="0" class="inputfield gradientField" />
											</td>
										
											<td>
												<input type="text" id="numberOfRolls" value="10000" class="inputfield gradientField" />
											</td>
											<td>
												<select id="display" class="inputfield gradientField" >
													<option value="individual">Individual values</option>
													<option value="batch">Batch</option>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="3">
												<div id="rollScientific" class="link mainButton button">Roll</div>
												<img id="calculating" src="images/loading.png" alt="Loading image..."/>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" value="Send" /> 
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="scrollButtons">
			<div id="scrollLeft" class="link button scrollButton">Help</div>
			<div id="scrollRight" class="link button scrollButton right">Stat</div>
		</div>
	</body>
</html>