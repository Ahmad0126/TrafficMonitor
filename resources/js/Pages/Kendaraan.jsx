import { Head, router } from "@inertiajs/react";
import { useState } from 'react';
import Alerts from "./Components/Alerts";
import Footer from "./Components/Footer";
import Navbar from "./Components/Navbar";
import Sidebar from "./Components/Sidebar";

export default function Kendaraan(props) {
    const [jenis, setJenis] = useState('');
    const [id, setId] = useState('');
    const [alert, setAlert] = useState(false);
    const [isEdit, setIsEdit] = useState(false);

    const handleSubmit = () => {
        const data = { jenis, id }
        if(isEdit){
            router.post(props.url_edit, data)
        }else{
            router.post(props.url_tambah, data)
        }
        setAlert(true);
        setJenis('');
        setId('');
        setIsEdit(false);
    }

    const handleEdit = (d) => {
        setIsEdit(true);
        setJenis(d.jenis)
        setId(d.id)
    }

    console.log('props last: ', props);

    return (
        <>
            <Head title={props.title} />
            <div className="wrapper">
                <Sidebar></Sidebar>
                <div className="main-panel">
                    <Navbar></Navbar>
                    <div className="container">
                        <div className="page-inner">
                            <div className="row">
                                <div className="col">
                                    <div className="card">
                                        <div className="card-header">
                                            <div className="card-title">{isEdit? 'Edit' : 'Tambah'} Jenis</div>
                                        </div>
                                        <div className="card-body">
                                            {alert && <Alerts msg={props.flash.alert} color="success" />}
                                            {alert && <Alerts msg={props.errors.jenis} color="danger" />}
                                            <div className="form-group">
                                                <div className="input-group">
                                                    <input type="text" name="jenis" id="" className="form-control" onChange={(i) => setJenis(i.target.value)} value={jenis} placeholder="Nama Jenis Kendaraan" />
                                                    <button className="btn btn-secondary" type="submit" onClick={() => handleSubmit()}>{isEdit? 'Edit' : 'Tambah'}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col">
                                    <div className="card">
                                        <div className="card-header">
                                            <div className="card-title">Daftar Jenis Kendaraan</div>
                                        </div>
                                        <div className="card-body">
                                            <div className="table-responsive">
                                                <table className="table table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Nama Kendaraan</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        { props.kendaraan.data ?
                                                            props.kendaraan.data.map((data, i) => {
                                                                return <tr key={i}>
                                                                    <th scope="row">{1 + i}</th>
                                                                    <td>{data.jenis}</td>
                                                                    <td>
                                                                        <button className="btn btn-sm btn-secondary"
                                                                            onClick={() => handleEdit({ id: data.id, jenis: data.jenis })}>
                                                                            Edit
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            })
                                                            :
                                                            <tr><td className="text-center" colSpan={3}>Belum Ada Data</td></tr>
                                                        }
                                                    </tbody>
                                                </table>
                                            </div>
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