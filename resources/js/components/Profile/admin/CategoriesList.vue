<template>
    <select name="" id="parent_id" class="form-control" v-model="parentID" @change.prevent="onChangeParentID"
            v-html="tree">
    </select>
</template>

<script>
    import VueBus from "vue";

    export default {
        name: "CategoriesList",
        props: ['categories', 'selectedID'],
        data() {
            return {
                parentID: '',
                tree: '',
                allCategories: {}
            }
        },
        methods: {
            onChangeParentID() {
                Event.$emit('formParentID', [this.parentID, this.selectedID]);
            },
            creatingTree(categories = this.allCategories, delimiter = '', tree = '<option value="">None</option>') {
                for (let category of categories) {
                    if (!category.children) {
                        // category has not children
                        tree += `<option value=${category.id}>${delimiter}${category.title}</option>`;
                    } else {
                        tree += `<option value=${category.id}>${delimiter}${category.title}</option>`;
                        // category has children
                        delimiter += '----';
                        //get children of category
                        let children = category.children;

                        //pass that children to recursion function as categories and take back data to our tree
                        tree = this.creatingTree(children, delimiter, tree);
                        for(let child of children){
                            for(let i in this.allCategories){
                                if(this.allCategories[i].id == child.id){
                                    this.allCategories.splice(i,1);
                                    break;
                                }
                            }

                        }
                        delimiter = delimiter.slice(4);

                    }

                }


                return tree;

            },
            getSelected() {
                if (this.selectedID) {
                    this.parentID = this.selectedID;
                }
            },
            propsHandler() {
                this.allCategories = JSON.parse(JSON.stringify(this.categories));
                this.tree = this.creatingTree();
            }
        },
        mounted() {
            this.getSelected();
        },
        watch: {
            categories: [{
                handler: 'propsHandler'
            }],
            selectedID: [{
                handler: 'getSelected'
            }]
        },


    }
</script>

<style scoped>

</style>
