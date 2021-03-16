import Index        from "./components/Index";
import NotFound     from "./components/NotFound";
import Welcome      from "./components/Welcome";
import Home         from "./components/Home";
import Profile from "../modules/main/profile/Profile";
import GamblingList from "../modules/main/gambling/GamblingList";
import RouletteList from "../modules/main/roulette/RouletteList";
import MarketList from "../modules/main/market/MarketList";
import CompositionList from "../modules/main/compositions/CompositionList";
import Favourites from "../modules/main/profile/Favourites";
import GamblingView from "../modules/main/gambling/GamblingView";
import RouletteView from "../modules/main/roulette/RouletteView";
import MarketView from "../modules/main/market/MarketView";
import Inventory from "../modules/main/profile/Inventory";
import Workbench from "../modules/main/compositions/Workbench";
import CompositionView from "../modules/main/compositions/CompositionView";

// Load modules routes dynamically.
const requireContext = require.context('../modules', true, /routes\.js$/)

const modules = requireContext.keys()
    .map(file =>
        [file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)]
    )

let moduleRoutes = [];

for(let i in modules) {
    moduleRoutes = moduleRoutes.concat(modules[i][1].routes)
}

export const routes = [
    {
        path: '/admin',
        component: Home,
        meta: {auth: true},
        children: [
            ...moduleRoutes,
        ]
    },
    {
        path: '/',
        component: Welcome,
        children: [
            {
                path: '/',
                component: Index,
                name: 'index',
            },
            {
                path: '/workbench',
                component: Workbench,
                name: 'workbench'
            },
            {
                path: '/profile',
                meta: {auth: true},
                component: Profile,
                name: 'profile'
            },
            {
                path: '/inventory',
                meta: {auth: true},
                component: Inventory,
                name: 'inventory'
            },
            {
                path: '/favourites',
                meta: {auth: true},
                component: Favourites,
                name: 'favourites'
            },
            {
                path: '/gambling',
                component: GamblingList,
                name: 'gambling'
            },
            {
                path: '/gambling/:id/:name',
                component: GamblingView,
                name: 'View Gambling'
            },
            {
                path: '/roulette',
                component: RouletteList,
                name: 'roulette'
            },
            {
                path: '/roulette/:id/:name',
                component: RouletteView,
                name: 'View Roulette'
            },
            {
                path: '/markets',
                component: MarketList,
                name: 'markets'
            },
            {
                path: '/market/:id/:name',
                component: MarketView,
                name: 'View Market'
            },
            {
                path: '/compositions',
                component: CompositionList,
                name: 'compositions'
            },
            {
                path: '/composition/:id',
                component: CompositionView,
                name: 'View Composition'
            },
            {
                path: '*',
                component: NotFound,
                name: 'not_found'
            }
        ]
    },
];

