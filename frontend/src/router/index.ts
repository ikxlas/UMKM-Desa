import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/public/HomeView.vue'
import ProductView from '../views/public/ProductView.vue'
import CategoryView from '../views/public/CategoryView.vue'
import AboutView from '../views/public/AboutView.vue'
import HelpView from '../views/public/HelpView.vue'

// Admin Views
import AdminLayout from '../views/admin/AdminLayout.vue'
import DashboardView from '../views/admin/DashboardView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/product',
      name: 'product',
      component: ProductView,
    },
    {
      path: '/category',
      name: 'category',
      component: CategoryView,
    },
    {
      path: '/about',
      name: 'about',
      component: AboutView,
    },
    {
      path: '/help',
      name: 'help',
      component: HelpView,
    },
    {
      path: '/admin',
      component: AdminLayout,
      children: [
        {
          path: '',
          name: 'admin-dashboard',
          component: DashboardView,
        },
        {
          path: 'products',
          name: 'admin-products',
          component: () => import('../views/admin/ProductsView.vue'),
        },
        {
          path: 'merchants',
          name: 'admin-merchants',
          component: () => import('../views/admin/MerchantsView.vue'),
        },
        {
          path: 'categories',
          name: 'admin-categories',
          component: () => import('../views/admin/CategoriesView.vue'),
        },
        {
          path: 'banners',
          name: 'admin-banners',
          component: () => import('../views/admin/BannersView.vue'),
        },
        {
          path: 'featured',
          name: 'admin-featured',
          component: () => import('../views/admin/FeaturedProductsView.vue'),
        },
        {
          path: 'statistics',
          name: 'admin-statistics',
          component: () => import('../views/admin/StatisticsView.vue'),
        }
      ]
    }
  ],
})

export default router
