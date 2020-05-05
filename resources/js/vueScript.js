var table = new Vue(
    {
        el: "#vue",
        data: {
            //is table sorted in ascending orders
            asc: false,
            key: "",
            //items: All\\Item::all(),
            table: [];
        },

        mounted(){
          axios.get(Item::all()).then(({ data })=>{
            this.table=data
          });
        },

        methods: {
            //sorts table
            sortTable: function(){
                //uses js sort function
                this.items.sort((a, b) => {
                    if(a.name > b.name){
                        return 1;
                    }
                    else if (a.name < b.name){
                        return -1;
                    }
                    return 0;
                })
                //this reverses the order if the table has already been sorted
                if(this.asc == true){
                    this.items.reverse();
                }
                this.asc = !this.asc;
            }
        },

        computed: {
            //outputs filtered data
            filteredTable: function(){
                return this.items.filter((a) => {
                    //no filter means all are outputted
                    if(this.key == ""){
                        return true;
                    }
                    //this makes everything lowercase because caps are annoying
                    else{
                        return a.name.toLowerCase().match(this.key.toLowerCase());
                    }
                })
            },
            //calculates the name of the columns
            columns: function() {
                return Object.keys(this.items[0])
            }
        },

        filters: {
            //makes first letter capital
            capitalise: function (value) {
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        }
    }
);
