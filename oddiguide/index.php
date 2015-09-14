<?php include('header.php'); ?>
	
<style>

	ul {
		padding-bottom:15px;
	}

	li.question {
	  font-weight:bold;
	  font-size:1.3em;
	  padding-bottom:5px;
	}
	li.answer {
	 list-style: none;
	 padding-bottom: 15px;
	}

</style>

	<h2>FAQ</h2>
	<ul>
		<li class="question">How do I create a Table of Contents (TOC)?</li>
		<li class="answer">Pressbooks automatically generates one or more table of contents for each format.<BR /><BR />
			<ul>
				<li>EPUB MENU SYSTEM TOC: 
					<ul>
						<li>ePubs are required to include a TOC that is accessible via the menu system in most e-readers.</li>
						<li>Pressbooks automatically creates this for you and currently only outputs the first level of navigation.</li>
					</ul>
				</li>
				<li>EPUB EMBEDDED TOC: 
					<ul>
						<li>A Table of Contents is automatically embedded as part of the content of ePubs.</li>
						<li>You cannot disable this for ePubs as it is considered a critical best practice.</li>
						<li>You can include a second level by <a target="_blank" href='http://guide.pressbooks.com/chapter/table-of-contents-adding-a-second-level/'>enabling it in Pressbooks</a> and using the &lt;h1&gt; tag.</li>
					</ul>
				</li>
				<li>PDF BOOKMARKS: 
					<ul>
						<li>Pressbooks automatically creates bookmarks in the PDF for all of the chapters.</li>
						<li>If a second level  is enabled for the TOC, bookmarks will be created for it.</li>
					</ul>
				</li>
				<li>PDF EMBEDDED TOC: 
					<ul>
						<li>In PDF files you have the option to not output the embedded TOC.</li>
						<li>If you do include the embedded TOC you have the option for it to be two levels the same way as in ePub.</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
	<ul>
		<li class="question">How do I change the styles when editing text?</li>
		<li class="answer">There are two ways to edit styles to customize the appearance of your book: <BR /><BR />
			<ul>
				<li>You can click the <a target='new' href='toolbartoggle.png'>toolbar toggle</a> button when editing text and it will reveal a number of formatting options.</li>
				<li>You can go to Appearance -> Edit CSS in the left navigation and edit actual CSS rules there.  You must be sure to select either ePub or mPDF as the output.  The <a target='new'  href='http://guide.pressbooks.com/chapter/customizing-your-exports/'>Pressbooks Instructions on Custom CSS</a> may prove useful as well.</li>
			</ul>
		</li>
	</ul>
	<ul>
		<li class="question">How do I change the font?</li>
		<li class="answer">In order to streamline the process of eBook creation, we are only providing a single font for each language.  We have to use open source fonts so that we can embed them in the ePubs.</li>
	</ul>
	

	

<?php include('footer.php'); ?>