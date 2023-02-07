<!-- 
A very simple input form View template:
note that the form method is POST, and the action
is the URL for the route that handles form input.

@BASE = base url, e.g. jlee.edinburgh.com
 -->


<p>This is a simple form</p>
<form id="form1" name="form1" method="post" action="<?= ($BASE) ?>/simpleform">
  Enter Name, Pet and Colour:
  <input name="name" type="text" placeholder="Steve" id="name" size="50" />
  <input name="colour" type="text" placeholder="color" id="colour" size="50" />
  <input name="pet" type="text" placeholder="pet" id="pet" size="50" />
<p>
  <input type="submit" name="Submit" value="Submit" />
</p>
</form>
