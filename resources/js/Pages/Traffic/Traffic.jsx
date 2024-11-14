import { Head } from "@inertiajs/react";
import Footer from "../Components/Footer";
import Navbar from "../Components/Navbar";
import Sidebar from "../Components/Sidebar";
import DataTraffic from "./DataTraffic";
import Paginator from "./Paginator";

export default function Traffic(props) {
    return (
        <>
            <Head title={props.title} />
            <div className="wrapper">
                <Sidebar></Sidebar>
                <div className="main-panel">
                    <Navbar></Navbar>
                    <div className="container">
                        <div className="page-inner">
                            <div className="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                                <div>
                                    <h3 className="fw-bold mb-3">Traffic</h3>
                                </div>
                                <div className="ms-md-auto py-2 py-md-0">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_filter" className="btn btn-label-info btn-round me-2">Filter</a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#buat_grafik" className="btn btn-primary btn-round">Buat Grafik</a>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col">
                                    <div className="card">
                                        <div className="card-header">
                                            <div className="card-title">Daftar Lalu Lintas</div>
                                        </div>
                                        <div className="card-body">
                                            <div className="table-responsive">
                                                <table className="table table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Tanggal</th>
                                                            <th>Jenis Kendaraan</th>
                                                            <th>Kecepatan</th>
                                                            <th>Ruas Jalan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <DataTraffic traffic={props.traffic.data} start={props.traffic.meta.from} />
                                                    </tbody>
                                                </table>
                                            </div>
                                            <Paginator links={props.traffic.meta.links} />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Footer></Footer>
                </div>
            </div>
        </>
    )
}