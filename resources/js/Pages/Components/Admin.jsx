import Footer from "./Footer";
import Navbar from "./Navbar";
import Sidebar from "./Sidebar";

export default function Admin(){
    return(
        <>
            <div className="wrapper">
                <Sidebar></Sidebar>
                <div className="main-panel">
                    <Navbar></Navbar>
                    <div className="container">
                        <div className="page-inner">
                            <h1>Hello World</h1>
                        </div>
                    </div>
                    <Footer></Footer>
                </div>
            </div>
        </>
    )
}