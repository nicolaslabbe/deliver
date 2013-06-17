$(document).ready(function() {
	$("#formulaire input[type=checkbox]").on("click", function() {
		if($(this).attr("checked") == "checked") {
			check($(this));
		}else {
			uncheck($(this));
		}
	});
	
	function check(obj) {
		// unselect next one
		obj.parent("div").children("div").each(function() {
			$(this).css("background-color", "#69dd76");
			$(this).children("input[type=checkbox]").eq(0).attr("checked", "checked");
			check($(this).children("input[type=checkbox]").eq(0));
		});
		
		obj.parent("div").css("background-color", "#69dd76").attr("data-selected", "true");
	}
	
	function uncheck(obj) {
		obj.parent("div").css("background-color", "#dd6981").attr("data-selected", "false");
		
		obj.parent("div").children(".children").each(function() {
			$(this).css("background-color", "#dd6981").attr("data-selected", "");
			if($(this).children("input[type=checkbox]").hasClass("folder") && $(this).children("input[type=checkbox]").attr("checked") !== undefined) {
				uncheck($(this).children("input[type=checkbox]").eq(0));
			}
			$(this).children("input[type=checkbox]").each(function() {
				$(this).removeAttr("checked")
			});
		});
	}
	
	$(".export").on("click", function() {
		if(confirm("Are you sure ?")) {
			$("#formulaire").submit();
		}
	});
	
	$(".showColor").removeAttr("checked");
	$(".showColor").on("click", function() {
		var tabs = [], checked = $(this).attr("checked"), color = "";
		$(".showColor").removeAttr("checked");
		if(checked != undefined) {
			$(this).attr("checked", "checked");
			color = $(this).val();
		}
		if(color != "") {
			$(".children").each(function() {
				if($(this).css("background-color") == color) {
					$(this).show();
					tabs.push($(this));
				}else {
					$(this).hide();
				}
			});
			for(var i = 0 ; i < tabs.length ; i++) {
				showParent(tabs[i]);
			}
		}else {
			$(".children").show();
		}
	});
	
	var cpt = 0;
	function showParent(obj) {
		cpt++;
		var parent = obj.parent("div:hidden");
		if(parent.html() != null) {
			parent.show();
			showParent(parent);
		}
	}
});