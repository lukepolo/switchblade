jQuery.fn.getPath = function () 
{
    // We must have somthing as an input
    if (this.length == 1)
    {
        var path, node = this;
        
        // Checks to see if the node has an ID, if it does we just use that!
        if (node[0].id)
        {
            return "#" + node[0].id;
        }
        
        // While a node has some length we have to add it the tree
        while (node.length) 
        {
            // Node is in a single array
            var realNode = node[0],
            name = realNode.localName;
            if (!name) 
            {
                break;
            }
            
            // Lowercase for formatting
            name = name.toLowerCase();
            var parent = node.parent();
            // Get all the siblings
            var siblings = parent.children(name);
            
            // If they have a sibling we will have to find which the current node is
            if (siblings.length > 1) 
            {
                name += ':eq(' + siblings.index(realNode) + ')';
            }
            // Path is complete
            path = name + (path ? ' > ' + path : '');
            
            // The parent is now the new node , loop till done
            node = parent;
        }
        return path;
    }
};