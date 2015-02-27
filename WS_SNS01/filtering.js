$(document).ready(function()
{
    $('table.messageTab').exTableFilter(
    {
        filters : 
        {
            0 : 
            {
                append : 
                {
                    to : 'span.sender-filter-area',
                    type : 'select'
                }
            },
            
            1 : 
            {
                append : 
                {
                    to : 'span.receiver-filter-area',
                    type : 'select'
                }
            },
            
            2 : 
            {
                append : 
                {
                    to : 'span.privacy-filter-area',
                    type : 'select'
                }
            },
            
            4 : 
            {
                append : 
                {
                    to : 'span.message-filter-area',
                    type : 'text'
                }
            },

        }
    }
    );
}
);