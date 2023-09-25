import storageRead2 as sr
from BasisAPI2 import API as BasisAPI
import click, json, requests, random

def handler_for_visid(name):
    return name.replace(f'{Loader.sysname}@@', "")

def handler_for_dict(list, type_of_obj):
    data = []
    for unit in list:
        elid = unit["entity"]["elid"]
        if type_of_obj == 'FS':
            unit["entity"] = basisApi.query_fs(elid)
            unit["entity"]["type"] = "FILESYSTEM"
            unit["entity"]["visibleId"] = handler_for_visid(unit["entity"]["visibleId"])
            unit["entity"]["link"] = unit.get("relation").get("linkElid")
            data.append(unit["entity"])
        else:
            type = basisApi.get_type_of_obj(elid)
            if type == type_of_obj:
                if type == "VOLUME":
                    unit["entity"] = basisApi.query_vol(elid)
                elif type == "LUN":
                    unit["entity"] = basisApi.query_lun(elid)
                elif type == "QTREE":
                    unit["entity"] = basisApi.query_qtree(elid)
                unit["entity"]["type"] = type
                unit["entity"]["visibleId"] = handler_for_visid(unit["entity"]["visibleId"])
                unit["entity"]["link"] = unit.get("relation").get("linkElid")
                unit["entity"]["fs"] = []
                data.append(unit["entity"])
                
    return data

def get_all_units_from_sys(sysElid):
    sys_units = {}
    units = basisApi.get_units_from_sys(sysElid)
    sys_units["volumes"] = handler_for_dict(units, "VOLUME")
    for volume in sys_units["volumes"]:
        volume['fs'] = handler_for_dict(basisApi.get_fs_from_volume(volume['elid']), 'FS')
    sys_units["luns"] = handler_for_dict(units, "LUN")
    for lun in sys_units["luns"]:
        lun['fs'] = handler_for_dict(basisApi.get_fs_from_lun(lun['elid']), 'FS')
    sys_units["qtrees"] = handler_for_dict(units, "QTREE")
    for qtree in sys_units["qtrees"]:
        qtree['fs'] = handler_for_dict(basisApi.get_fs_from_qtree(qtree['elid']), 'FS')
    return sys_units

def all_units_dicty(unit_list):
    all_units_dict = {}
    for volume in unit_list['volumes']:
        all_units_dict[handler_for_visid(volume["visibleId"])] = {"elid": volume.get("elid"), "link": volume.get("link"), "type": volume.get("type")}
        for fs in volume["fs"]:
            all_units_dict[handler_for_visid(fs["visibleId"])] = {"elid": fs.get("elid"), "link": fs.get("link"), "type": fs.get("type")}
    for lun in unit_list["luns"]:
        all_units_dict[handler_for_visid(lun["visibleId"])] = {"elid": lun.get("elid"), "link": lun.get("link"), "type": lun.get("type")}
        for fs in lun["fs"]:
            all_units_dict[handler_for_visid(fs["visibleId"])] = {"elid": fs.get("elid"), "link": fs.get("link"), "type": fs.get("type")}
    for qtree in unit_list["qtrees"]:
        all_units_dict[handler_for_visid(qtree["visibleId"])] = {"elid": qtree.get("elid"), "link": qtree.get("link"), "type": qtree.get("type")}
        for fs in qtree["fs"]:
            all_units_dict[handler_for_visid(fs["visibleId"])] = {"elid": fs.get("elid"), "link": fs.get("link"), "type": fs.get("type")}
    
    return all_units_dict

def del_unlink_unit(unit, delete = False):
    success = True
    if unit['type'] == "FILESYSTEM":
        if not basisApi.unlink_fs_to_unit(unit['link'], unit['elid']): success = False
        if delete:
            basisApi.delete_fs(unit['elid'])
    else:
        basisApi.unlink_unit_from_sys(Loader.sysname, unit['link'])
        if delete:
            if unit['type'] == "VOLUME":
                for i in basisApi.get_fs_from_volume(unit['elid']):
                    basisApi.delete_fs(i['entity']['elid'])
                basisApi.delete_volume(unit['elid'])
            elif unit['type'] == "LUN":
                for i in basisApi.get_fs_from_lun(unit['elid']):
                    basisApi.delete_fs(i['entity']['elid'])
                basisApi.delete_lun(unit['elid'])
            elif unit['type'] == "QTREE":
                for i in basisApi.get_fs_from_qtree(unit['elid']):
                    basisApi.delete_fs(i['entity']['elid'])
                basisApi.delete_qtree(unit['elid'])
            
    return success

class Loader():
    sysname: str = ""

if __name__ == "__main__":
    
    @click.command()
    @click.option("--systorage", "-sys", default = "Netapp-TestLoader", required = True, type = str, help = "SystemStorage name")
    @click.option("--compare", "-com", default = False, required = True, type = bool, help = "compare with old file")
    @click.option("--host", "-url", default = "10.161.60.30", required = True, type = str, help = "IP basis")
    @click.option("--port", "-p", default = "8080", required = True, type = str, help = "Port basis")
    @click.option("--protocol", "-pr", default = "http", required = True, type = str, help = "Protocol")
    @click.option("--username", "-un", default = "sdi-api", required = True, type = str, help = "Basis username")
    @click.option("--password", "-pw", default = "basis", required = True, type = str, help = "Basis password")
    @click.option("--mandant", "-man", default = "1001", required = True, type = str, help = "Basis domen")
    @click.option("--group", "-gr", default = "sdi-api", required = True, type = str, help = "Basis group")
    def test(systorage, compare, host, port, protocol, username, password, mandant, group):
        global basisApi
        basisApi = BasisAPI(url=host,
                        port=port,
                        protocol=protocol,
                        username=username,
                        password=password,
                        mandant=mandant,
                        group=group
                        )
        
        syselid = basisApi.getStorageSystemByName(systorage)
        Loader.sysname = syselid
        structure = get_all_units_from_sys(syselid)
        if not compare:
            with open ('systorage.json', 'w') as file:
                json.dump(structure, file, indent=4)
            
            all = all_units_dicty(structure)
            
            print(json.dumps(all) + '\n\n')
            
            data = list(all.keys())
            for i in range(random.randint(1,4)):
                to_del = random.choice(data)
                if del_unlink_unit(all[to_del]):
                    print(f'Unlinked {all[to_del]["type"]} — {to_del}')
                else: print(f'Error for {to_del}')
                data.remove(to_del)
                
            for i in range(random.randint(1,4)):
                to_del = random.choice(data)
                if del_unlink_unit(all[to_del], True):
                    print(f'Deleted {all[to_del]["type"]} — {to_del}')
                else: print(f'Error for {to_del}')
                data.remove(to_del)
        else:
            with open ('systorage.json', 'r') as file:
                old_struct = json.loads(file.read())
            
            if all_units_dicty(structure).keys() == all_units_dicty(old_struct).keys():
                print("Success")
            else: print("Not concides")
        
    test()