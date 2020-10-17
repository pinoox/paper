<template>
    <div class="page">
        <div class="search-bar">
            <i class="icon fas fa-sliders-h"></i>
            <input class="search-input" type="text" placeholder="جست و جو بین نوشته ها...">
        </div>

        <div class="container">
            <div class="section">
                <div class="section-content">
                    <vue-good-table
                            styleClass="vgt-table table"
                            :rtl="true"
                            :columns="columns"
                            :rows="rows"
                            :pagination-options="{
    enabled: true,
    mode: 'records',
    perPage: 5,
    position: 'bottom',
    perPageDropdown: [3, 7, 9],
    dropdownAllowAll: false,
    setCurrentPage: 2,
    nextLabel: 'بعدی',
    prevLabel: 'قبلی',
    rowsPerPageLabel: 'تعداد ردیف در صفحه',
    ofLabel: 'از',
    pageLabel: 'صفحه', // for 'pages' mode
    allLabel: 'همه',
  }">
                        >
                        <template slot="table-row" slot-scope="props">
                            <div v-if="props.column.field == 'thumb'">
                                <img class="thumb thumb-round" :src="props.row.thumb" alt="">
                            </div>
                            <div v-else>
                                {{props.formattedRow[props.column.field]}}
                            </div>
                        </template>
                    </vue-good-table>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                columns: [
                    {
                        label: 'تصویر',
                        field: 'thumb',
                    },
                    {
                        label: 'عنوان',
                        field: 'title',
                    },
                    {
                        label: 'وضعیت',
                        field: 'status',
                    },
                    {
                        label: 'تاریخ',
                        field: 'insert_date',
                    },
                ],
                rows: [
                    {
                        id: 1,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید1',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 2 روز پیش',
                    },
                    {
                        id: 2,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید2',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 1 روز پیش',
                    },
                    {
                        id: 3,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید3',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 3 روز پیش',
                    },
                    {
                        id: 1,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید1',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 2 روز پیش',
                    },
                    {
                        id: 2,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید2',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 1 روز پیش',
                    },
                    {
                        id: 3,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید3',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 3 روز پیش',
                    },
                    {
                        id: 1,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید1',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 2 روز پیش',
                    },
                    {
                        id: 2,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید2',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 1 روز پیش',
                    },
                    {
                        id: 3,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید3',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 3 روز پیش',
                    },
                    {
                        id: 1,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید1',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 2 روز پیش',
                    },
                    {
                        id: 2,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید2',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 1 روز پیش',
                    },
                    {
                        id: 3,
                        thumb: require(`@img/sample-user.jpg`),
                        title: 'روزه داری در شرایط بحران ویروس کووید3',
                        status: 'منتشر شده',
                        insert_date: 'شنبه 3 روز پیش',
                    },
                ],
            }
        }
    }
</script>