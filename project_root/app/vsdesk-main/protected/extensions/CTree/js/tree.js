

(function()
{
	if($.moco === null)
	{
		var moco = {};
		$.moco = moco;
	} 

	if($.moco.env == null)
	{
		$.moco.env = {};

	}

	var Tree = function ()
	{
		this.id = Math.random() * 1000 + 7*3;
		this.sufix = '';

		this.data = {};
		this.nodes = {};
		this.selected = {};

		this.url = '';
		this.ajaxLoad = false;

		//whether to allow multi select
		this.multi = false;

		this.width = 0;
		this.height = 0;
		
		this.isLoading = false;
		
		this.firstSelect = false;

		this.openAll = false;

		//Future use: used when render the link tree (menu style) 
		this.nodeTemplate = '<div name="{@nodeId}">{@nodeTitle}</div>';

		//used when this.multi is false
		this.radioTemplate = '<div class="radio-custom" id="node-{@nodeId}" >'+
                             '    <div class="radio-button b-2" type="selector" style="background-position: 0pt -12px;" onClick="(function(event) { $.moco.trees[\'{@treeId}\'].nodes[\'{@nodeId}\'].nodeSelect(event); })(event)"></div>'+
                             '    <div class="radio-label">{@nodeTitle}</div>'+
                             '    <input type="radio" class="hiden-radio b-2" name="nodes-{@treeId}" id="nodes-{@treeId}"   {@selected} value="{@value}">'+
							 '    <div class="children"></div>'+
                             ' </div>';

		//used when this.multi is true  {@nodeId}
		this.checkboxTemplate = '<div class="custom-checkbox" id="node-{@nodeId}" >'+
								'	<div class="checkbox-button hov" type="selector" onClick=" (function(event) { $.moco.trees[\'{@treeId}\'].nodes[\'{@nodeId}\'].nodeSelect(event);})(event); "></div>'+
								'	<div class="checkbox-label">{@nodeTitle}</div>'+
								'	<input type="checkbox" name="{@nodeId}" id="{@nodeId}" {@selected}>'+
								'   <div class="children" style="padding-left:23px;"></div>'+
								' </div>';

		this.onLoad = null;
		this.onRefresh = null;
		this.onSelect = null;
		this.onClick = null;
		this.onOpen = null;
		this.onClose = null;
		
		this.parseOptions = function(options)
		{
			tree.id = options["id"];
			tree.sufix = options["sufix"];			
			tree.url = options["url"];
			tree.multi = options["multi"];
			tree.width = options["width"];
			tree.height = options["height"];
			tree.ajaxLoad = options["ajaxLoad"];
			tree.openAll = options["openAll"];
			tree.firstSelect = options["firstSelect"];
		}
		
		this.init = function ()
		{
			var height = '';
			if(this.height == 0)
			{
				height = this.height+"%";
			}
			else
			{
				height = this.height+"px";
			}
			$("#tree-content-"+this.sufix).css("height", height);
			
			var width = '';
			if(this.width == 0)
			{
				width = this.width+"%";
			}
			else
			{
				width = this.width+"px";
			}
			$("#tree-content-"+this.sufix).css("width", width);
			
			this.refresh();
		}

		this.clearSelection = function()
		{
			$('input:radio').removeAttr('checked');
			$('div[class*=radio-button]').css('background-position', '0 0px');

			$(this).parent().find('.checkbox-button:first').css('background-position', '0 0');
		}

		this.nodeSelect = function (node, event)
		{

			if(!this.multi)
			{
				this.selected = [{id: node.nodeId, text: node.nodeTitle}];
				this.selected.id = node.nodeId;
				this.selected.text = node.nodeTitle;

				$('#node-'+node.nodeId+' > div[class*=radio-button]').parent().parent().parent().parent().find('.radio-button').css('background-position', '0 0');
				$('input:radio').removeAttr('checked');
				$('#node-'+node.nodeId+' > div[class*=radio-button]').css('background-position', '0 -12px');
				$('#node-'+node.nodeId+' > div[class*=radio-button]').parent().find('input:first:radio').attr('checked', 'checked');
			}
			else
			{
				if(this.selected[node.nodeId] == null)
				{
					var newNode = [{id : '', text : ''}];
					newNode.id = node.nodeId;
					newNode.text = node.nodeTitle;

					this.selected[node.nodeId] = newNode;

					$('#node-'+node.nodeId).find('.checkbox-button:first').css('background-position', '0 -13px');
					$('#node-'+node.nodeId).find('input:first:checkbox').attr('checked', 'checked');
				}
				else
				{
					this.selected[node.nodeId] = null;

					$('#node-'+node.nodeId).find('.checkbox-button:first').css('background-position', '0 0');
					$('#node-'+node.nodeId).find('input:first:checkbox').removeAttr('checked');
				}
			}
			
			if(this.onSelect != null)
			{
				this.onSelect(node, event);
			}
		}

		this.nodeClick = function(node, event)
		{

			this.nodes[this.getNodeId(node)].selected = !this.nodes[this.getNodeId(node)].selected;

			if(this.nodes[this.getNodeId(node)] && this.ajaxLoad)
			{
				$.moco.env['event'] = event;

				this.nodes[this.getNodeId(node)].onLoad = function() { this.tree.isLoading = false; this.tree.nodeActions( this, $.moco.env['event'] ); if(this.tree.onLoad != null) this.tree.onLoad(this); $.moco.env['event'] = null; }
				this.nodes[this.getNodeId(node)].loadChildren();
			}
			else
			{
				this.nodeActions( this.nodes[this.getNodeId(node)], event );
			}

			if(event != null && ($.browser.msie == null || $.browser.msie != true || $.browser.msie === true && ($.browser.version == '9.0' || $.browser.version == '8.0')))
			{
				event.preventDefault();
				event.stopPropagation();
			}

			if(this.onClick != null)
			{
				this.onClick(this.nodes[this.getNodeId(node)], event);
			}

		}

		this.nodeActions = function(node, event)
		{
			this.renderNodes( node );

			node.isOpen = !node.isOpen;
			if(!node.isOpen)
			{
				this.nodeClose(node);
			}
			else
			{
				this.nodeOpen(node);
			}

			if(event != null && ($.browser.msie !== true || $.browser.msie === true && ($.browser.version == '9.0' || $.browser.version == '8.0')))
			{
				event.preventDefault();
				event.stopPropagation();
			}
		}

		this.nodeOpen = function(node)
		{
			$("#"+node.id+' > [class*="children"]').show();
			$("#node-"+node.nodeId+' > [class*="children"]').show();
		}

		this.nodeClose = function(node)
		{
			$("#"+node.id+' > [class*="children"]').hide();
			$("#node-"+node.nodeId+' > [class*="children"]').hide();
		}

		this.getNodeId = function(node)
		{
			var nodeId = node.id;
			nodeId = nodeId.replace(/node-/, "");
			return nodeId;
		}

		this.getTemplate = function()
		{
			var template = '';
			if(this.multi)
			{
				template = this.checkboxTemplate;
			}
			else
			{
				template = this.radioTemplate;
			}

			return template;
		}

		this.renderSingleNode = function (node)
		{
			var result = this.getTemplate();

			for (var ind in node)
			{
				result = result.replace(new RegExp('{@'+ind+'}', 'g'), node[ind]);
			}

			return result;
		}

		this.setParentStyle = function(nodeLabel)
		{
			nodeLabel.css("cursor", "pointer");
			nodeLabel.css("text-decoration", "dashed");
			nodeLabel.css("display", "inline-block");
			nodeLabel.css("border-bottom", "1px dashed");
			nodeLabel.css("padding-bottom", "3px");			
		}

		this.renderNodes = function(node)
		{
			if(node == null)
			{
				for(var ind in this.nodes)
				{
					$("#tree-content-"+this.sufix).html($("#tree-content-"+this.sufix).html() + this.nodes[ind].renderNode());

					if(!this.nodes[ind].showSelector)
					{
						$("#node-"+this.nodes[ind].nodeId+' > [type*=selector]').css("display", "none");
					}

					//set up onClick event
					$("#node-"+this.nodes[ind].nodeId).attr("onclick", "$.moco.trees['"+this.id+"'].nodeClick(this, event); "); //'#node-'" + this.nodes[ind].nodeId)"

					if(this.nodes[ind].hasChildren || this.nodes[ind].children.length > 0)
					{
						this.setParentStyle($("#node-"+this.nodes[ind].nodeId+" > div[class*=label]"));
					}
				}

				if(this.firstSelect)
				{
					for(var ind in this.nodes)
					{
						this.nodeSelect(this.nodes[ind]);
						break;
					}
				}
				else
				{
					this.clearSelection();
				}
			}
			else
			{
				var nodeObj = ((this.nodes[node.nodeId] != null) ? this.nodes[node.nodeId] : this.nodes[this.getNodeId(node)]);

				if(nodeObj.children != null && nodeObj.children.length > 0 || nodeObj.hasChildren)
				{
					var childrenHtml = null;

					if($("#node-"+node.nodeId+' > div[class*=children]').length < 1)
					{
						$("#node-"+node.nodeId).html($("#node-"+node.nodeId).html() + "<div class='children'></div>");
						childrenHtml = $("#"+node.id+' > div[class*=children]');
					}

					childrenHtml = $("#node-"+node.nodeId+' > div[class*=children]');
					childrenHtml.html ('');

					for(var ind in nodeObj.children)
					{ 
						var node = new TreeNode();
						node.tree = this;
						node.parseOptions(nodeObj.children[ind]);

						this.nodes[node.nodeId] = node;

						childrenHtml.html(childrenHtml.html()+ " " + this.nodes[node.nodeId].renderNode());

						if(this.nodes[node.nodeId].hasChildren || this.nodes[node.nodeId].children.length > 0)
						{
							this.setParentStyle($("#node-"+node.nodeId+" > div[class*=label]"));
						}

						if(!this.nodes[node.nodeId].showSelector)
						{
							$("#node-"+this.nodes[node.nodeId].nodeId+' > [type*=selector]').css("display", "none");
						}

						//set up onClick event
						$("#node-"+node.nodeId).attr("onClick", " $.moco.trees['"+this.id+"'].nodeClick(this, event); "); //'#node-'" + this.nodes[ind].nodeId)"
					}

					if(this.firstSelect)
					{
						for(var ind in nodeObj.children)
						{
							this.nodeSelect(this.nodes[nodeObj.children[ind]['nodeId']]);
							break;
						}
					}
					else
					{
						this.clearSelection();
					}
				}
			}
		}

		this.refresh = function()
		{
			$("#tree-content-"+this.sufix).html('');

			if(this.ajaxLoad && this.url.length > 0)
			{
					
				while($.moco.env['treeId']); // wait while the last request finished

				this.isLoading = true;

				$.moco.env['treeId'] = this;

				$.ajax( { url: this.url, cache: false, type: "POST", async: false,
							success: function (data) {

									$.moco.env["treeId"].data = jQuery.parseJSON(data);

									$.moco.env["treeId"].initNodes();

									if($.moco.env["treeId"].onLoad != null)
									{
										$.moco.env["treeId"].onLoad();
									}

									$.moco.env["treeId"].isLoading = false;
									$.moco.env["treeId"] = null;

									$.moco.env['selectedId'] = null;
							} } );
			}
			else
			{
				this.initNodes();
			}

			if(this.onRefresh != null)
			{
				this.onRefresh();
			}
		}
		
		this.initNodes = function()
		{
			if(this.data.length > 0)
			{
				for(var ind in this.data)
				{
					var node = new TreeNode();
					node.tree = this;
					node.parseOptions(this.data[ind]);

					this.nodes[node.nodeId] = node;
				}
			}

			this.renderNodes();
		}
	}

	var TreeNode = function ()
	{
		this.nodeId = Math.round(Math.random() * 10000 + 7*13);
		this.nodeTitle = '';
		this.selected = false;
		this.value = '';

		this.treeId = '';

		this.tree = null;
		this.parent = null;
		this.children = {};
		this.hasChildren = false;
		this.showSelector = true;

		this.nodeUrl = '';
		this.nodeTemplate = '';

		this.isOpen = false;

		this.renderParams = ['nodeTitle', 'nodeId', 'treeId', 'value'];
		
		this.parseOptions = function(options)
		{
			if(options["nodeUrl"] != null && options["nodeUrl"] !== '' )
			{
				this.nodeUrl = options["nodeUrl"];
			}
			else
			{
				this.nodeUrl = this.tree.url;
			}

			if(options["nodeId"] != null)
				this.nodeId = options["nodeId"];

			if(this.treeId == '' && this.tree != null)
			{
				this.treeId = this.tree.id;
			}

			if(options["nodeTitle"] !== null)
				this.nodeTitle = options["nodeTitle"];

			if(options['nodeTemplate'] != null)
				this.nodeTemplate = options['nodeTemplate'];
			else
				this.nodeTemplate = this.tree.getTemplate();

			if(options['showSelector'] != null && options['showSelector'] == false)
			{
				this.showSelector = false;
			}

			if( ((options["nodeChildren"] != null) && $.isArray(options["nodeChildren"]) && options["nodeChildren"].length > 0) || (options["hasChildren"] == true))
			{
				this.children = options["nodeChildren"];
				this.hasChildren = true;
			}

			if(options["isOpen"] != null && options["isOpen"] !== false)
			{
				this.isOpen = true;
			}
		}

		this.loadChildren = function()
		{
		
			$.moco.env['node'] = this;
			
			$.moco.env['node'].tree.isLoading = true;

			$.ajax( { url: this.nodeUrl + "?id=" + this.nodeId, acync: false, cache: false, type: "POST", 
							success: function (data) {

									$.moco.env['node'].children = jQuery.parseJSON(data);

									if($.moco.env['node'].onLoad != null)
									{
										$.moco.env['node'].onLoad();
									}
									
									$.moco.env['node'].tree.isLoading = false;

							} } );
		}

		this.nodeSelect = function(event)
		{
			if(event != null && ($.browser.msie === true && ($.browser.version == '9.0' || $.browser.version == '8.0')))
			{
                event.preventDefault();
			}

			if(event != null)
				event.stopPropagation();

			this.tree.nodeSelect(this, event); 
		}

		this.renderNode = function()
		{
			var result = this.nodeTemplate;

			if(this.value == '')
				this.value = this.nodeId;

			for(var ind in this.renderParams)
			{
				var regExp = new RegExp("{@"+this.renderParams[ind]+"}", "g");
				result = result.replace(regExp, this[this.renderParams[ind]]);
			}

			regExp = new RegExp("{@selected}", "g");
			if(this.selected === true)
			{
				result = result.replace(regExp, "checked='"+this.nodeId+"'");
			}
			else
			{
				result = result.replace(regExp, "");
			}

			if(this.isOpen) // need to render child nodes
			{
				
			}

			return result;
		}
	}

	$.moco = $.extend($.moco, { trees : {} });

	$.moco.trees = $.extend($.moco, { add : function(obj, options)
		{
			
			if(obj !== null)
			{
				$.moco.trees[obj.id] = obj;
			}
			else if(options !== null)
			{
				tree = new Tree();
				tree.parseOptions(options);

				$.moco.trees[tree.id] = tree;
				obj = tree;
			}

			return $.moco.trees[obj.id];
		}
	 });

})(jQuery);