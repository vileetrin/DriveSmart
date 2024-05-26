import Cars from "./components/cars/Cars";
import {BrowserRouter, Route, Routes} from 'react-router-dom'
import MainPage from "./pages/MainPage/MainPage";

function App() {
  return (
    // <div className="App">
    //   <Cars />
    // </div>
    <BrowserRouter>
    <Routes>
        <Route path="/" element={<MainPage />} />
        <Route path="/cars" element={<Cars />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
