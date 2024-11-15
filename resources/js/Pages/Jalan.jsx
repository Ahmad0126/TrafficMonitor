import { Head, router } from "@inertiajs/react";
import { useState } from 'react';
import Alerts from "./Components/Alerts";
import Footer from "./Components/Footer";
import Navbar from "./Components/Navbar";
import Sidebar from "./Components/Sidebar";

export default function Jalan(props) {
    const [ruas, setRuas] = useState('');
    const [id, setId] = useState('');
    const [alert, setAlert] = useState(false);
    const [isEdit, setIsEdit] = useState(false);

    const handleSubmit = () => {
        const data = { ruas, id }
        if(isEdit){
            router.post(props.url_edit, data)
        }else{
            router.post(props.url_tambah, data)
        }
        setAlert(true);
        setRuas('');
        setId('');
        setIsEdit(false);
    }

    const handleEdit = (d) => {
        setIsEdit(true);
        setRuas(d.ruas)
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
                                            <div className="card-title">{isEdit? 'Edit' : 'Tambah'} Ruas</div>
                                        </div>
                                        <div className="card-body">
                                            {alert && <Alerts msg={props.flash.alert} color="success" />}
                                            {alert && <Alerts msg={props.errors.ruas} color="danger" />}
                                            <div className="form-group">
                                                <div className="input-group">
                                                    <input type="text" name="ruas" id="" className="form-control" onChange={(i) => setRuas(i.target.value)} value={ruas} placeholder="Nama Ruas Jalan" />
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
                                            <div className="card-title">Daftar Ruas Jalan</div>
                                        </div>
                                        <div className="card-body">
                                            <div className="table-responsive">
                                                <table className="table table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Nama Jalan</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        { props.jalan.data ?
                                                            props.jalan.data.map((data, i) => {
                                                                return <tr key={i}>
                                                                    <th scope="row">{1 + i}</th>
                                                                    <td>{data.ruas}</td>
                                                                    <td>
                                                                        <button className="btn btn-sm btn-secondary"
                                                                            onClick={() => handleEdit({ id: data.id, ruas: data.ruas })}>
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