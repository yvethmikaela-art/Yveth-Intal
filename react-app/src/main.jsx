import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import Login from './Login'
import Register from './Register'
import Dashboard from './Dashboard'

const path = window.location.pathname
const user = window.__CI4_USER__ || null

const root = createRoot(document.getElementById('root'))

if (path.includes('/register')) root.render(<StrictMode><Register /></StrictMode>)
else if (path.includes('/dashboard')) root.render(<StrictMode><Dashboard user={user} /></StrictMode>)
else root.render(<StrictMode><Login /></StrictMode>)