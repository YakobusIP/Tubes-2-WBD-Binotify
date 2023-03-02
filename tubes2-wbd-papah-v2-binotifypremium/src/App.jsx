import { Routes, Route } from 'react-router-dom';

import LoginRedirect from './components/LoginRedirect/LoginRedirect';
import ManageSongs from './pages/ManageSongs/ManageSongs';
import SubscriptionList from './pages/SubscriptionList/SubscriptionList';
import Login from './pages/Login/Login';
import Register from './pages/Register/Register';

function App() {
  return (
    <>
      <div className="content">
        <Routes>
          <Route path='/' element={ <LoginRedirect/> } />
          <Route path='/manage-songs' element={ <ManageSongs/> } />
          <Route path='/subscription-list' element={ <SubscriptionList/> } />
          <Route path='/login' element={ <Login/> } />
          <Route path='/register' element={ <Register /> } />
        </Routes>
      </div>
    </>
  )
}

export default App
