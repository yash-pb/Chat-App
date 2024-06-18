import Layout from "../components/Layout/Layout.vue";

const routes = [
    // {
    //     path: 'login',
    //     name: 'login', 
    //     component: Login
    // },
    // {
    //     path: 'forgot-password',
    //     name: 'forgot.password',
    //     component: ForgotPassword
    // },
    // {
    //     path: 'change-password',
    //     name: 'change.password',
    //     component: ChangePassword
    // },
    {
        path: '/',
        name: 'index', 
        component: Layout,
        meta: {
            requiresAuth: true
        },
        // children: [
        //     {
        //         path: 'dashboard',
        //         name: 'dashboard', 
        //         component: Dashboard
        //     },
        //     {
        //         path: 'users',
        //         name: 'users', 
        //         component: Users
        //     },
        //     {
        //         path: 'users/create',
        //         name: 'user.create', 
        //         component: UserCreate
        //     },
        //     {
        //         path: 'users/edit/:id',
        //         name: 'user.edit', 
        //         component: UserEdit,
        //         props: true
        //     },
        //     {
        //         path: 'books',
        //         name: 'books', 
        //         component: Books
        //     },
        //     {
        //         path: 'books/create',
        //         name: 'book.create', 
        //         component: BookCreate
        //     },
        //     {
        //         path: 'books/edit/:id',
        //         name: 'book.edit', 
        //         component: BookEdit,
        //         props: true
        //     },
        // ]
    }   
];

export default routes;