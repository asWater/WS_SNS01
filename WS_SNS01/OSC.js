/**
 * [O description]
 * @param {[type]} obj [description]
 *
 *  Return the object itself, when it receives the object.
 *  Return the object referred by ID, when it receives the ID.
 */
function O(obj)
{
	if(typeof obj == 'object') 
	{
		return obj
	}
	else
	{
		return document.getElementById(obj)
	}
}


/**
 * [S description]
 * @param {[type]} obj [description]
 *
 *  This function returns Style property of received objects/IDs.
 *  When you give object not ID to this function, you need to add quotation mark ('').
 */
function S(obj)
{
	return O(obj).style
}

/**
 * [C description]
 * @param {[type]} name [description]
 *
 * This function returns the array which holds all elements identified by the class property of HTML.
 * The objective of this function is to change styles of all elements identified by the same class property.
 */
function C(name)
{
	var elements = document.getElementsByTagName('*')
	var objects = []

	for (var i = 0; i < elements.length; i++)
	{
		if (elements[i].className == name)
		{
			objects.push(elements[i])
		}

		return objects
	}
}