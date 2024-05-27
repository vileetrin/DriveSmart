import Cars from "./components/cars/Cars";
import {BrowserRouter, Route, Routes} from 'react-router-dom'
import MainPage from "./components/cars/MainPage";
import Catalog from "./components/cars/Catalog";

function App() {
  return (
    // <div className="App">
    //   <Cars />
    // </div>
    <BrowserRouter>
    <Routes>
        <Route path="/" element={<MainPage />} />
        <Route path="/cars" element={<Cars />} />
        <Route path="/Catalog" element={<Catalog />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
