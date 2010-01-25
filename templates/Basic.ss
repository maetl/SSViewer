<html>
<head>
<% base_tag %>
</head>
<body>
	<% control Menu(1) %>
		<p>Menu Item: $Item</p>
	<% end_control %>
	
	<% if Page.IsFeatured %>
		<p>Featured $Page.Title</p>
		<span>$Element.Reference('hello world')</span>
	<% end_if %>
	
	<% control Paginator(1, 2, 3) %>
		<p>$PageNumber</p>
	<% end_control %>
	
	<%-- a template comment --%>
	
	$Object.Element.Reference.Field.Meta.Value
	
	<p><% if Expression %><span>1</span><% else_if Expression2 %><span>2</span><% end_if %>
		
	$Object.Element(2).Reference.Call('a', 'b', 'c')
	
	<% include Footer %>
	
	<%-- literal quoted symbols are now supported --%>
	
	$StringArg(symbol)
	$NumberArg(111)
	$QuotedStringArg('hello world')
	
</body>
</html>