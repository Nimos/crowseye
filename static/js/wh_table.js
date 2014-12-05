var system = "";
var lastSystem = "!";
var lastUpdate = 0;

function handleJump(from, to) {
	var wormholeName = "";
	var systemName = "";
	if (/J[0-9][0-9][0-9][0-9][0-9][0-9]/.test(from)) {
		wormholeName = from;
		systemName = to;
	} else if (/J[0-9][0-9][0-9][0-9][0-9][0-9]/.test(to)) {
		systemName = from;
		wormholeName = to;
	}

	if (wormholeName == "") return;
	if (systemName == "") return;
	$.ajax({
      	    type: 'POST',
      	    cache: false,
      	    url: 'api/wh',
      	    data: "systemName="+systemName+"&wormholeName="+wormholeName+"&sites=&comment=",
      	    success: function (msg) {
      	    	if (msg != "ERROR_EXISTS") {
    				$('#sitesModal').find('.whName').text(wormholeName);
					$('#sitesModal').find('#saveSites').click(function () {

						var whName=$('#sitesModal').find('.whName').text();
				  	 	$('#sitesModal').find('textarea').addClass('loading').attr('disabled', 'disabled');
				   		$.ajax({
							type: 'POST',
							cache: false,
							url: '/api/sites/'+$('tr[name='+whName+']').attr('id'),
							data: 'sites='+$('#sitesModal').find('textarea').val(),
							success: function (data) {
								getWhData();
								$('#sitesModal').find('textarea').removeAttr('disabled'); 
								$('#sitesModal').find('textarea').removeClass('loading').val('');
								$('#sitesModal').modal('hide');
							}
						});
				   	 });
    				$('#sitesModal').modal('show');
      	    	}
      	    }
    });

}

function getWhData () {
	var debug = false;
	$.getJSON("/api/wh", function (data) {
		if (debug) console.log("debug");

		var holes = {};
		$('tr.wh').each(function () {
			if (debug) console.log("debug");
			var id = $(this).attr('id');
			holes[id] = [0, null];
		});

		for (c=0;c<data.length;c++) {
			if (debug) console.log("debug");
			if (holes[data[c].id] != undefined) {
				var isFiltered = (!$('button#'+$('#'+data[c].id).children("td.class").text().substr(0,2)).hasClass('btn-success'));//ugly :(
				if (!isFiltered) holes[data[c].id][0] = 1; //keep
			} else {
				var isFiltered = (!$('button#'+data[c].class).hasClass('btn-success'));//ugly :(				
				if (!isFiltered) holes[data[c].id] = [2, data[c]]; //add
			}
		}

		var result = [];
		$.each(holes, function (id, hole) {
			if (debug) console.log("debug");
			var whclass = $('#'+id).children("td.class").text().substr(0,2); //used for filters

			if (hole[0] == 0) { // hole expired or filtered
				delWh(id);
				return true;
			} else if (hole[0] == 2) {
				addWh(hole[1]);
			}

			result.push(id);
		});

		$.post("/api/information/"+result.join(','), "after="+lastUpdate, function (data) {

			$.each(data.comments, function (hole, comments) {
				if (debug) console.log("debug");
				$.each(comments, function (id, comment) {
					if (debug) console.log("debug");
					if (debug) console.log(comment);
					var exists = $('tr#'+hole).next('.info').find('li.comment#'+comment[4]).length > 0;
					if (exists) return true;
					var row = '<li class="comment list-group-item" id="'+comment[4]+'">';
						row+= '  <p class="list-group-item comment-head">'+'<span class="age" title="'+(new Date(comment[0]*1000)).toISOString()+'"></span><span>, </span><span class="comment_author">'+comment[1].charName+'</span> wrote:';
						row+= '  <p class="comment list-group-item">'+comment[2]+'</p>';
						row+= '</li>';
		
					$('tr#'+comment[3]).next('.info').find('ul.comments').children().last().before(row);
					$('.age').timeago();
				});
			});

			$.each(data.sites, function (hole, sites) {
				if (debug) console.log("debug");
				if (sites["all"].length == 0) {
					$('tr#'+hole).children('td.siteNumber').text("No Report");
				} else {
					$('tr#'+hole).children('td.siteNumber').text(sites["Combat Site"].length);
				}
				siteCount = $('tr#'+hole).next('.info').find('tr.site').length;
				$('tr#'+hole).next('.info').find('table.sites>tbody:last').html('');
				for (var c=0; c<sites.all.length; c++) {
					if (debug) console.log("debug");
					if(c==-1) {c++}
					site = sites.all[c];
					if (site[1] == "") site[1] = "-";
					if (site[2] == "") site[2] = "-";
					var row = '<tr class="site">';
						row+= '  <td>'+site[0]+'</td>';
						row+= '  <td>'+site[1]+'</td>';
						row+= '  <td>'+site[2]+'</td>';
						row+= '</tr>';
					$('tr#'+hole).next('.info').find('table.sites').append(row);
				}
			});

			if (data.igbheaders.trusted) {
				if (window['system'] != data.igbheaders.solarSystemName) {
					handleJump(window['system'], data.igbheaders.solarSystemName);
					window['lastSystem'] = window['system'];
					window['system'] = data.igbheaders.solarSystemName;
				}
			}
		 	if (debug) console.log("debug");


			lastUpdate = Math.floor((new Date()).getTime()/1000);
		}, "json");
	});
	
}

function addWh (wh) {
	lastUpdate = 0;
	var warning = false;
	var siteNumber = wh.siteNumber;
	if (wh.sites.all.length == 0) siteNumber="No Report";

	var row = '<tr jumps="'+wh.jumps+'" style="display:none;" class="wh" id="'+wh.id+'" name="'+wh.name+'">';
		row+= '	 <td class="jumps">'+wh.jumps+'</td>';
		row+= '  <td class="system">'+wh.system+'</td>';
		row+= '  <td class="class '+wh.class+'_wh">'+wh.class+'</td>';
		row+= '  <td class="wh_name">'+wh.name+'  <a class="tp" data-toggle="tooltip" title="Open on wh.pasta.gg" onclick="event.stopPropagation()" target="_blank" href="http://wh.pasta.gg/'+wh.name+'">  <img src="static/gfx/wormholes.png" width="16" height="16"></a></td>';
		row+= '  <td class="sigid">'+wh.sig+'</td>';
		row+= '  <td class="siteNumber">'+siteNumber+'</td>';
		row+= '  <td class="reporter">';
		row+= '    <span class="character_field" name="'+wh.reporter[1]+'" style="background-image: url(http://image.eveonline.com/corporation/'+wh.reporter[2]+'_32.png), url(\'http://image.eveonline.com/character/'+wh.reporter[1]+'_32.jpg\')">'+wh.reporter[0]+'</span>';
		row+= '  </td>';
		row+= '  <td class="age" title="'+(new Date(wh.reported*1000)).toISOString()+'">'+wh.reported+'</td>';
		row+= '  <td>&#9660;</td>'
		row+= '</tr>';
		row+= '<tr class="info" style="display:none;" id="'+wh.id+'">';
		row+= '  <td colspan="4">';
		row+= '    <div style="display:none;">';
		row+= '      <h4>Sites</h4>';
		row+= '      <div class="info sites">';
		//row+= '        <p class="sitecount">Total Sites:'+wh.sites.all.length+'</p>';
		row+= '        <table class="sites table table-condensed">';
		row+= '          <thead>';
		row+= '            <th>Sig ID</th>';
		row+= '            <th>Type</th>';
		row+= '            <th>Name</th>';
		row+= '          </thead>';
		row+= '          <tbody>';
		$.each(wh.sites.all, function (key, site) {
			if (site[1] == "") site[1] = "-";
			if (site[2] == "") site[2] = "-";
			row+='<tr class="site"><td>'+site[0]+'</td><td>'+site[1]+'</td><td>'+site[2]+'</td></tr>';
		});
		row+= '          </tbody>';
		row+= '        </table>';
		if (isIngameBrowser || isAuthed) {
			row+= '     <hr>';
			row+= '     <div class="form-group">';
			row+= '       <label for="sites">Update Signature List</label>';
        	row+= '       <textarea class="form-control" rows="5" name="sites" id="sites"></textarea>';
        	row+= '       <p class="help-block">Paste your probe scanner output here.</p>';
        	row+= '       <button class="btn-xs btn-default siteupdate" style="margin-top: 5px;">Save</button>';
        	row+= '     </div>'
		}
		row+= '      </div>';
		row+= '    </div>';
		row+= '  </td>';
		row+= '  <td colspan="7">';
		row+= '    <div class="info">';
		row+= '      <button class="delete button" id="'+wh.id+'">Delete Wormhole</button>';
		row+= '      <h4>Additional Information</h4>';
		row+= '    </div>';
		row+= '    <div style="display:none;">';
		row+= '      <h4>Comments</h4>';
		row+= '      <ul class="comments list-group">';
		$.each(wh.comments, function (key, comment) {
			row+= '<li class="comment list-group-item" id="'+comment[4]+'">';
			row+= '  <p class="list-group-item comment-head">'+'<span class="age" title="'+(new Date(comment[0]*1000)).toISOString()+'"></span><span>, </span><span class="comment_author">'+comment[1].charName+'</span> wrote:';
			row+= '  <p class="comment list-group-item">'+comment[2]+'</p>';
			row+= '</li>';
		});
		row+= '        <li class="form-group">';
		if (isIngameBrowser || isAuthed) {
			row+= '          <textarea placeholder="Your Comment..." class="form-control write_comment list-group-item"></textarea>';
			row+= '          <button class="btn-xs btn-default comment" style="margin-top: 5px;">Send</button>';
		}
		row+= '        </li>';
		row+= '      </ul>';
		row+= '    </div>';
		row+= '  </td>';
		row+= '</tr>';

	var first = true;
	if (wh.jumps != ">5") {
		for (j=0;j<=6;j++) {
			if ($('tr.wh[jumps='+j+']').length > 0) {
				first = false;
				if (wh.jumps <= j) {
					$('tr.wh[jumps='+j+']:first').before(row);
					break;
				}
			}
		}
		if ($('tr.wh[jumps=">5"]').length > 0) {
			$('tr.wh[jumps=">5"]:first').before(row);
		}
	} else {first = false;}
	if (first) {
		$("tbody.holes:last").prepend(row);
	} else {
		$("tbody.holes:last").append(row);;
	}

	$(".delete").click(function (e) {
        $.post('/api/delete', {id: $(this).attr('id')});
	});

	if (wh.effect) {
		var effectname = wh.effect[0].replace(" ", "").replace("	","");
		var effectreadable = wh.effect[0].substr(1);
		if (effectname.length > 1) {
			$('tr.wh#'+wh.id).children('.class').append('<span class="tp effect '+effectname+'" data-toggle="tooltip" title="'+effectreadable+'">  &#9679;</span>');
		}
	}
	$('.tp').each(function() {$(this).tooltip();});
	$('.age').timeago();
	
	$('tr.wh#'+wh.id).click(function () {
		if ($(this).next('.info').is(":visible")) {
			$(this).next('.info').find('div').slideUp('fast', function () {
				console.log($(this));
				$(this).parents('tr').hide();
			});
			$(this).find('td:last').html('&#9660;');
		} else {
			$(this).next('.info').show().find('div').slideDown('fast');
			$(this).find('td:last').html("&#9650;");
		}
	});
	$('tr.wh#'+wh.id).next('.info').find('button.comment').click(function () {
		$(this).prev('textarea').attr('disabled', 'disabled'); //Disable
		$(this).prev('textarea').addClass('loading');
		$.ajax({
			type: 'POST',
			cache: false,
			url: '/api/comments/'+$(this).parents('tr.info').attr('id'),
			data: 'text='+$(this).prev('textarea').val(),
			success: function () {
				getWhData();
				$('textarea[disabled]').removeAttr('disabled'); //Enable
				$('textarea').removeClass('loading').val("");
			}
		});
	});
	$('tr.wh#'+wh.id).next('.info').find('button.siteupdate').click(function () {
		$(this).prev().prev('textarea').attr('disabled', 'disabled'); //Disable
		$(this).prev().prev('textarea').addClass('loading');
		$.ajax({
			type: 'POST',
			cache: false,
			url: '/api/sites/'+$(this).parents('tr.info').attr('id'),
			data: 'sites='+$(this).prev().prev('textarea').val(),
			success: function () {
				getWhData();
				$('textarea[disabled]').removeAttr('disabled'); //Enable
				$('textarea').removeClass('loading').val("");
			}
		});
	});

	$('tr.wh#'+wh.id).find('.character_field').click(function (e) {
		e.stopPropagation();
		CCPEVE.showInfo(1377, $(this).attr('name'));
	});

	$('tr.wh#'+wh.id).fadeIn(2000);

}

function delWh (id) {
	$('tr#'+id).fadeOut(2000, function () {
		$(this).remove();
		$('tr.info#'+id).remove();
	});
}



function openAddModal() {
	var system = window['system'];
	var lastSystem = window['lastSystem'];
	if (/J[0-9][0-9][0-9][0-9][0-9][0-9]/.test(window['system'])) {
		$('#addModal').find('input#systemName').val(window['lastSystem']);
		$('#addModal').find('input#wormholeName').val(window['system']);
	} else if (/J[0-9][0-9][0-9][0-9][0-9][0-9]/.test(window['lastSystem'])) {
		$('#addModal').find('input#systemName').val(window['system']);
		$('#addModal').find('input#wormholeName').val(window['lastSystem']);		
	} else {
		$('#addModal').find('input#systemName').val(window['system']);
	}

	$('#addModal').modal('show');
}

$('form#addWhForm').submit(function(e){
	if ($(this).find('#systemName').val() == "" || $(this).find('#wormholeName').val() == "") return false;

	if (!/J[0-9][0-9][0-9][0-9][0-9][0-9]/.test($(this).find('#wormholeName').val())) {
      	$('button#addHole').removeClass('loading');
      	$('#addModal').find('#wormholeName').parent().addClass('has-error');
      	$('.modal-footer').before('<div class="alert alert-danger fade in">\
      		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>\
     		<h4>You got an error!</h4>\
     			<p>For now, only wormholes to w-space are allowed.</p>\
    		</div>');		
      	return false;
	}

	$('button#addHole').addClass('loading');
      	e.preventDefault();
      	$.ajax({
      	    type: 'POST',
      	    cache: false,
      	    url: 'api/wh',
      	    data: $(this).serialize(), 
      	    success: function(msg) {
      	    	if (msg != -1) {
      	    		$('#addModal').modal('hide');
      	    		$('#addModal').find('input,textarea').val('');
      	    		$('button#addHole').removeClass('loading');
      	    		$('#addModal').find('#wormholeName').parent().removeClass('has-error');
      	    	} else {
      	    		$('button#addHole').removeClass('loading');
      	    		$('#addModal').find('#wormholeName').parent().addClass('has-error');
      	    		$('.modal-footer').before('<div class="alert alert-danger fade in">\
      					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>\
     					 <h4>You got an error!</h4>\
     					 <p>The Wormhole you were trying to add is already in the Database or not a valid System</p>\
    					</div>');
      	    	}
        }
    });
});     

$('button.filterbutton').click(function () {
	$(this).toggleClass('btn-success');
	getWhData();
});
	
$("select#homeSystem").change(function () {
	$.cookie('home', $(this).val());
	location.reload();
});