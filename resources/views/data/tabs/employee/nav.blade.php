<nav v-if="pagination.total>0">
    <ul class="pagination">
        <li v-if="pagination.current_page > 1">
            <a href="#" aria-label="First" title="First" @click.prevent="changePage(1)">
                <i class="fa fa-angle-double-left" aria-hidden="true"></i>
            </a>
        </li>
        <li v-if="pagination.current_page > 1">
            <a href="#" aria-label="Previous" title="Previous" @click.prevent="changePage(pagination.current_page - 1)">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </a>
        </li>
        <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
            <a href="#" @click.prevent="changePage(page)">
                @{{ page }}
            </a>
        </li>
        <li v-if="pagination.current_page < pagination.last_page">
            <a href="#" aria-label="Next" title="Next" @click.prevent="changePage(pagination.current_page + 1)">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        </li>
        <li v-if="pagination.current_page < pagination.last_page">
            <a href="#" aria-label="Last" title="Last" @click.prevent="changePage(pagination.last_page)">
                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
            </a>
        </li>
    </ul>
</nav>