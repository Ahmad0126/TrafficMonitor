import { Link } from "@inertiajs/react"

export default function Paginator({links}){
    if(links){
        return <>
            <div className="dataTables_paginate paging_simple_numbers p-4">
                <ul className="pagination" style={{float: 'right', gap: '5px'}}>
                    {links.map((data, i) => {
                        if(i == 0){
                            return <li key={i} className="paginate_button page-item previous">
                                <Link href={data.url} className="page-link">
                                    Prev
                                </Link>
                            </li>
                        }else if(i == links.length - 1){
                            return <li key={i} className="paginate_button page-item next">
                                <Link href={data.url} className="page-link">
                                    Next
                                </Link>
                            </li>
                        }else if(data.active){
                            return <li key={i} className="paginate_button page-item active">
                                <Link href="#" className="page-link">
                                    { data.label }
                                </Link>
                            </li>
                        }else if(data.url == null){
                            return <li key={i} className="disabled" aria-disabled="true"><span>{ data.label }</span></li>
                        }else{
                            return <li key={i} className="paginate_button page-item">
                                <Link href={data.url} className="page-link">
                                    { data.label }
                                </Link>
                            </li>
                        }
                    })}
                </ul>
            </div>
        </>
    }
    return <div></div>
}