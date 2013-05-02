This CMS system originated as a project management tool, but has accidentally grown into a system with potential to create any type of data-based application.

This first implementation allows you to create flexible groupings of data as "Content Types". Each Content Type acts as the definition to create a "node", which is a specific item of content. Nodes can be arranged into one of several "Views". 

In this version the only three Views available are:
	- Sortable Table
	  This lists data and allows the user to specify which columns ("Fields" when setting up a Content Type) will be shown. Each column can be reordered by clicking the column name
	- Task board
	  A task board is a colourful collection of "Cards" (which are just nodes). Multiple columns can be defined to enable the user to drag a node between different states. A good example of this would be an agile task board, where a task can be moved from pre-production to development, testing and finally sign off as work progresses.
	- Checklist
	  This View simply lists nodes (again with sortable columns, similar to the Table View), but allows the user to check off completed items.
	  
The Dashboard allows you to create each of these types, but with the end goal in sight. Simply click "Create... Table" to create a table. The system will walk you through the Content Type and View Set up before dropping you at the view itself where you can start adding your content.

To install:
- Download the files to a directory in your localhost. The database is set up to run from a database named "intranet", but this can be changed in application/config/database.php - you can also update your username and password here if necessary.

- Create your "intranet" table in your MySQL database. Run the intranet.sql file (located in the root folder) in your database management software (copy and paste or select the file to run).

- Navigate to your base url ("http://localhost/yourbaseurl"). The first time you run, it will ask you at add a user. Please note, if you're running this locally there's probably not much use selecting "Email the user". Once you've added that user (as the default permission "Admin"), you can then add users, Content Types and Views to your heart's content.

Some of the field types don't work as yet - notably the file upload. I will update as soon as this functionality is complete.

Enjoy!