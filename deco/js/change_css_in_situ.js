function changeCSS() {
	// https://stackoverflow.com/a/19844696/3566819
	cssLinkIndex=2; // starting from 0, the N-th <link ...> element in <head> that corresponds to the theme.css file to be replaced

	// "change the Id accordingly"
	fname=document.getElementById("css-chooser").value;
	
	if (fname == '_default_') {
		document.cookie = 'theme=""; Max-Age=-1; path=/';
		location.reload();
	}
	
	// here assumes the file exists!
	cssFile = '/deco/css/theme_' + fname + '.css';
	// document.write(cssFile);
    var oldlink = document.getElementsByTagName("link").item(cssLinkIndex);
	// document.write(oldlink.href);

    var newlink = document.createElement("link");
    newlink.setAttribute("rel", "stylesheet");
    newlink.setAttribute("type", "text/css");
    newlink.setAttribute("href", cssFile);
	// document.write(newlink.href);

	// update cookie. expire in 30 days
	document.cookie = "theme="+fname+'; max-age=86400*30; path=/;'

    document.getElementsByTagName("head").item(0).replaceChild(newlink, oldlink);
}