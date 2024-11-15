export default function DataTraffic({traffic, start}){
    if(traffic && start){
        return traffic.map((data, i) => {
            return <tr key={i}>
                <th scope="row">{start+i}</th>
                <td>{ data.tanggal }</td>
                <td>{ data.jenis }</td>
                <td>{ data.kecepatan } Km/h</td>
                <td>{ data.ruas }</td>
            </tr>
        })
    }
    return <tr><td className="text-center" colSpan={6}>Belum Ada Data</td></tr>
}