export default function Paginator({links}){
    if(links){
        return <>
            <div className="dataTables_paginate paging_simple_numbers p-4">
                <ul className="pagination" style={{float: 'right', gap: '5px'}}>
                    {links.map((data, i) => {
                        if(i == 0){
                            return <li key={i} className="paginate_button page-item previous">
                                <a href={data.url} className="page-link">
                                    Prev
                                </a>
                            </li>
                        }else if(i == links.length - 1){
                            return <li key={i} className="paginate_button page-item next">
                                <a href={data.url} className="page-link">
                                    Next
                                </a>
                            </li>
                        }else if(data.active){
                            return <li key={i} className="paginate_button page-item active">
                                <a href="#" className="page-link">
                                    { data.label }
                                </a>
                            </li>
                        }else if(data.url == null){
                            return <li key={i} className="disabled" aria-disabled="true"><span>{ data.label }</span></li>
                        }else{
                            return <li key={i} className="paginate_button page-item">
                                <a href={data.url} className="page-link">
                                    { data.label }
                                </a>
                            </li>
                        }
                    })}
                </ul>
            </div>
        </>
    }
    return <div></div>
}