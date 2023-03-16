<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<style type="text/css">
	@font-face { 
		font-family: "Roboto";
		src: url({{ '/storage/fonts/Roboto-Medium.ttf' }}) format("truetype");
		font-weight: 400;
	}
	@font-face { 
		font-family: "Roboto";
		src: url({{ '/storage/fonts/Roboto-Bold.ttf' }}) format("truetype");
		font-weight: bold;
	}

	* {font-family: Roboto, Inter, sans-serif;font-size: 14px;line-height: 14px;}
	
	</style>
</head>
<body>
	@include('pdf.contract.text')
</body>
</html>
