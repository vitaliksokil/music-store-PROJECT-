<template>

    <component :is="transformed()" v-bind="$props"></component>

</template>

<script>
    export default {
        name: "allCategoriesTree",
        props: ['allCats'],
        data() {
            return {
                allCategories: {},
                allCatsTree: '',
            }
        },
        methods: {
            allCategoriesTree(categories = this.allCategories, delimiter = 0, tree = '') {

                for (let category of categories) {
                    if (!category.children) {
                        // category has not children
                        tree += `<li style="margin-left: ${delimiter}px" value=${category.id}>

                                    <router-link :to="{name:'category',params:{id:${category.id}}}">
                                    <div class="category-list-item">
                                    <img src="/images/categories/${category.photo}" alt="">
                                    <h2 >${category.title}</h2>

                                    </div>

                                    </router-link>

                                    </li>`;
                    } else {
                        tree += `<li  style="margin-left: ${delimiter}px" value=${category.id}>
                                    <router-link :to="{name:'category',params:{id:${category.id}}}">
                                    <div class="category-list-item">
                                    <img src="/images/categories/${category.photo}" alt="">
                                    <h2 >${category.title}</h2>

                                    </div>

                                    </router-link>

                                    </li>`;
                        // category has children
                        delimiter += 30;
                        //get children of category
                        let children = category.children;

                        //pass that children to recursion function as categories and take back data to our tree
                        tree = this.allCategoriesTree(children, delimiter, tree);
                        for (let child of children) {
                            for (let i in this.allCategories) {
                                if (this.allCategories[i].id == child.id) {
                                    this.allCategories.splice(i, 1);
                                    break;
                                }
                            }

                        }
                        delimiter -= 30;

                    }

                }

                return tree;


            },
            transformed() {
                return {
                    template: this.allCatsTree,
                    props: this.$options.props
                }
            },
        },
        created() {
            this.allCategories = this.allCats;
            this.allCatsTree = this.allCategoriesTree(this.allCategories);
            this.allCatsTree = '<ul>' + this.allCatsTree + '</ul>';
            this.transformed();
        },
    }
</script>

<style>
.category-list-item img{
    max-width: 250px;
    max-height: 200px;
}
    .category-list-item{
        background: #e4e4e4;
        padding: 10px;
        border: 1px solid #777777;
        margin-bottom: 5px;
        border-radius: 5px;
        display: flex;
        align-items: center;


    }

</style>
