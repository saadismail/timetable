import webbrowser, bs4, re, requests

res = requests.get('https://sites.google.com/a/nu.edu.pk/info-cskhi/bscs-time-table-fall-17') # Get the whole page
res.raise_for_status() # Raise the exception if troubles in accessing the URL

soup = bs4.BeautifulSoup(res.text, "html.parser") # Load the page in BS4

text = soup.find('div', attrs={'id':re.compile('attachment-download-wuid.*')}) # Look for attachment-download-wuid.* as id in HTML of the page

# Exit if none or more than one elements with id attachment-download-wuid.* are found, if ever happens, it most probably will need a better way to find in the page
if len(text) != 1:
    sys.exit("Something went wrong with finding the specified id")

link = text.a.get('href') # Get the link of URL

link = "https://sites.google.com" + link # Modify the relative link to absolute path

webbrowser.open(link) # Open link in webbrowser
