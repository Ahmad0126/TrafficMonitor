import { Head, Link } from "@inertiajs/react";
import { useState } from 'react';
import Footer from "./Components/Footer";
import Navbar from "./Components/Navbar";
import Sidebar from "./Components/Sidebar";
import DataTraffic from "./DataTraffic";
import Paginator from "./Components/Paginator";

export default function Traffic(props) {
    const [id_ruas, setIdRuas] = useState('');
    const [id_jenis, setIdJenis] = useState('');
    const [kecepatan, setKecepatan] = useState('');
    const [logic_speed, setLogicSpeed] = useState('');
    const [tanggal1, setTanggal1] = useState('');
    const [tanggal2, setTanggal2] = useState('');
    const [order, setOrder] = useState('');

    console.log(props);

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
                            <div className="modal fade" id="modal_filter" tabIndex="-1">
                                <div className="modal-dialog modal-lg">
                                    <div className="modal-content">
                                        <div className="modal-header">
                                            <h1 className="modal-title fs-5">Filter</h1>
                                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div className="modal-body">
                                            <div className="row">
                                                <div className="form-group col-lg-4">
                                                    <label htmlFor="">Urutkan</label>
                                                    <select className="form-control form-select" onChange={(i) => setOrder(i.target.value)} name="order" id="">
                                                        <option value="terbaru">Terbaru</option>
                                                        <option value="terlama">Terlama</option>
                                                        <option value="tercepat">Kendaraan Tercepat</option>
                                                        <option value="terlambat">Kendaraan Terlambat</option>
                                                    </select>
                                                </div>
                                                <div className="form-group col-lg-4 col-md-6">
                                                    <label htmlFor="">Ruas Jalan</label>
                                                    <select className="form-control form-select" onChange={(i) => setIdRuas(i.target.value)} name="id_ruas" id="">
                                                        <option value="">-</option>
                                                        {props.jalan.data.map((data, i) => {
                                                            return <option key={i} value={ data.id }>{ data.ruas } </option>
                                                        })}
                                                    </select>
                                                </div>
                                                <div className="form-group col-lg-4 col-md-6">
                                                    <label htmlFor="">Jenis Kendaraan</label>
                                                    <select className="form-control form-select" name="id_jenis"  onChange={(i) => setIdJenis(i.target.value)} id="">
                                                        <option value="">-</option>
                                                        {props.kendaraan.data.map((data, i) => {
                                                            return <option key={i} value={ data.id }>{ data.jenis } </option>
                                                        })}
                                                    </select>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="form-group col-lg-3 col-md-6">
                                                    <label htmlFor="tanggal1">Dari Tanggal</label>
                                                    <input type="datetime-local" className="form-control" name="tanggal1" id="tanggal1" onChange={(i) => setTanggal1(i.target.value)} />
                                                </div>
                                                <div className="form-group col-lg-3 col-md-6">
                                                    <label htmlFor="tanggal2">Sampai Tanggal</label>
                                                    <input type="datetime-local" className="form-control" name="tanggal2" id="tanggal2" onChange={(i) => setTanggal2(i.target.value)} />
                                                </div>
                                                <div className="form-group col-lg-6">
                                                    <label htmlFor="kecepatan">Kecepatan</label>
                                                    <div className="input-group">
                                                        <select className="form-control form-select" name="logic_speed" id="" onChange={(i) => setLogicSpeed(i.target.value)} >
                                                            <option value="=">=</option>
                                                            <option value="kurang">{'<'}</option>
                                                            <option value="lebih">{'>'}</option>
                                                        </select>
                                                        <input className="form-control w-50" type="number" name="kecepatan" id="kecepatan" onChange={(i) => setKecepatan(i.target.value)} />
                                                        <span className="input-group-text" htmlFor="">Km/h</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="modal-footer">
                                            <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <Link className="btn btn-primary" data-bs-dismiss="modal" href={props.url_filter} as="button" method="get"
                                                data={{id_ruas: id_ruas, id_jenis: id_jenis, kecepatan: kecepatan,
                                                    logic_speed: logic_speed, tanggal1: tanggal1, tanggal2: tanggal2,
                                                    order: order}
                                                }>
                                                Terapkan
                                            </Link>
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