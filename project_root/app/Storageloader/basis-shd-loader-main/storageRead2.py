#!/usr/bin/python3

from BasisAPI2 import API as BasisAPI
from datetime import datetime
import logging, json, click


logger = logging.getLogger(__name__)

def add_missing_units(need_units, contained):
    for volume in need_units["volumes"]:
        search_vol = search_obj(contained, "volumes", volume["visibleId"])
        search_vol_in_wh = search_in_warehouse(volume["visibleId"], "volume")
        if search_vol:
            volumeElid = search_vol["elid"]
            basisApi.update_volume(volumeElid, form_obj_body(volume, False))
        elif search_vol_in_wh:
            volumeElid = search_vol_in_wh
            link = basisApi.link_unit_to_sys(Loader.syselid, volumeElid)
            search_vol = continue_structure(volumeElid, "volume", link)
            basisApi.update_volume(volumeElid, form_obj_body(volume))
        else:
            volumeElid = basisApi.create_volume(form_obj_body(volume))
            basisApi.link_unit_to_sys(Loader.syselid, volumeElid)
        for fs in volume["fs"]:
            search_fs = search_obj(search_vol, "fs", fs["visibleId"])
            search_fs_in_wh = search_in_warehouse(fs["visibleId"], "fs")
            if search_fs:
                fsElid = search_fs["elid"]
                basisApi.update_fs(fsElid, form_obj_body(fs, False))
            elif search_fs_in_wh:
                fsElid = search_fs_in_wh
                basisApi.link_fs_to_unit(volumeElid, fsElid)
                basisApi.update_fs(fsElid, form_obj_body(fs))
            else:
                fsElid = basisApi.create_fs(form_obj_body(fs))
                basisApi.link_fs_to_unit(volumeElid, fsElid)
    for lun in need_units["luns"]:
        search_lun = search_obj(contained, "luns", lun["visibleId"])
        search_lun_in_wh = search_in_warehouse(lun["visibleId"], "lun")
        if search_lun:
            lunElid = search_lun["elid"]
            basisApi.update_lun(lunElid, form_obj_body(lun, False))
        elif search_lun_in_wh:
            lunElid = search_lun_in_wh
            link = basisApi.link_unit_to_sys(Loader.syselid, lunElid)
            search_lun = continue_structure(lunElid, "lun", link)
            basisApi.update_lun(lunElid, form_obj_body(lun))
        else:
            lunElid = basisApi.create_lun(form_obj_body(lun))
            basisApi.link_unit_to_sys(Loader.syselid, lunElid)
        for fs in lun["fs"]:
            search_fs_lun = search_obj(search_lun, "fs", fs["visibleId"])
            search_fs_in_wh = search_in_warehouse(fs["visibleId"], "fs")
            if search_fs_lun:
                fsElid = search_fs_lun["elid"]
                basisApi.update_fs(fsElid, form_obj_body(fs, False))
            elif search_fs_in_wh:
                fsElid = search_fs_in_wh
                basisApi.link_fs_to_unit(lunElid, fsElid)
                basisApi.update_fs(fsElid, form_obj_body(fs))
            else:
                fsElid = basisApi.create_fs(form_obj_body(fs))
                basisApi.link_fs_to_unit(lunElid, fsElid)
    for qtree in need_units["qtrees"]:
        search_qtree = search_obj(contained, "qtrees", qtree["visibleId"])
        search_qtree_in_wh = search_in_warehouse(qtree["visibleId"], "qtree")
        if search_qtree:
            qtreeElid = search_qtree["elid"]
            basisApi.update_qtree(qtreeElid, form_obj_body(qtree, False))
        elif search_qtree_in_wh:
            qtreeElid = search_qtree_in_wh
            link = basisApi.link_unit_to_sys(Loader.syselid, qtreeElid)
            search_qtree = continue_structure(qtreeElid, "qtree", link)
            basisApi.update_qtree(qtreeElid, form_obj_body(qtree))
        else:
            qtreeElid = basisApi.create_qtree(form_obj_body(qtree))
            basisApi.link_unit_to_sys(Loader.syselid, qtreeElid)
        for fs in qtree["fs"]:
            search_qtree_fs = search_obj(search_qtree, "fs", fs["visibleId"])
            search_fs_in_wh = search_in_warehouse(fs["visibleId"], "fs")
            if search_qtree_fs:
                fsElid = search_qtree_fs["elid"]
                basisApi.update_fs(fsElid, form_obj_body(fs, False))
            elif search_fs_in_wh:
                fsElid = search_fs_in_wh
                basisApi.link_fs_to_unit(qtreeElid, fsElid)
                basisApi.update_fs(fsElid, form_obj_body(fs))
            else:
                fsElid = basisApi.create_fs(form_obj_body(fs))
                basisApi.link_fs_to_unit(qtreeElid, fsElid)
                    
                    
def continue_structure(elid, type, link):
    if type == "qtree":
        element = basisApi.query_qtree(elid)
        element["visibleId"] = handler_for_visid(element["visibleId"])
        element["link"] = link
        element["type"] = "QTREE"
        element['fs'] = handler_for_dict(basisApi.get_fs_from_qtree(element['elid']), "FS")
    if type == "lun":
        element = basisApi.query_lun(elid)
        element["visibleId"] = handler_for_visid(element["visibleId"])
        element["link"] = link
        element["type"] = "LUN"
        element['fs'] = handler_for_dict(basisApi.get_fs_from_lun(element['elid']), "FS")
    if type == "volume":
        element = basisApi.query_vol(elid)
        element["visibleId"] = handler_for_visid(element["visibleId"])
        element["link"] = link
        element["type"] = "VOLUME"
        element['fs'] = handler_for_dict(basisApi.get_fs_from_volume(element['elid']), 'FS')
    return element

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
    
def form_obj_body(obj, newLifeStatus = True):
    new_obj = obj.copy()
    to_del = ['type', 'fs', 'qtrees', 'luns', 'link', 'elid', 'id']
    for delete in to_del:
        try: new_obj.pop(delete) 
        except: pass
    if newLifeStatus:
        new_obj["lifecycleStatusDate"] = datetime.utcnow().isoformat() + "Z"
    new_obj["visibleId"] = f'{Loader.sysname}@@{new_obj["visibleId"]}'
    keys_to_del = []
    for key in new_obj.keys():
        if new_obj[key] == None:
            keys_to_del.append(key)
    for key in keys_to_del: new_obj.pop(key)

    return new_obj

def handler_for_visid(name):
    return name.replace(f'{Loader.sysname}@@', "")

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

def get_values_from_list(spisok, value):
    data = []
    if spisok:
        for i in spisok:
            data.append(i.get(value))

    return data

def search_obj(spisok, type, value):
    if spisok:
        for i in spisok.get(type):
            if handler_for_visid(i.get("visibleId")) == value:
                return i
    else: return None

def search_in_warehouse(visid, type):
    find = None
    value = Loader.sysname+'@@'+visid
    if type == "volume":
        find = basisApi.query_visid_vol(value)
    elif type == "lun":
        find = basisApi.query_visid_lun(value)
    elif type == "qtree":
        find = basisApi.query_visid_qtree(value)
    elif type == "fs":
        find = basisApi.query_visid_fs(value)
    return find
    
def delete_wrong_items(need_units, contained, delete = False):
    list_to_del = {}
    for unitvisid, unitvalues in all_units_dicty(contained).items():
        if unitvisid not in all_units_dicty(need_units).keys():
            unlink_any_obj(unitvalues["elid"], unitvalues["link"], unitvalues["type"])
            list_to_del[unitvalues["elid"]] = unitvalues["type"]
    if delete:
        for elid in list_to_del.keys():
            delete_any_obj(elid, list_to_del[elid])
        
def delete_any_obj(elid, type):
    if type == "VOLUME":
        basisApi.delete_volume(elid)
    if type == "FILESYSTEM":
        basisApi.delete_fs(elid)
    elif type == "LUN":
        basisApi.delete_lun(elid)
    elif type == "QTREE":
        basisApi.delete_qtree(elid)
    

def unlink_any_obj(elid, link, type):
    if type == "FILESYSTEM":
        basisApi.unlink_fs_to_unit(link, elid)
    else: basisApi.unlink_unit_from_sys(Loader.syselid, link)
    
def func(whattodo):
    controller = get_all_units_from_sys(Loader.syselid)
    if whattodo == "read":
        with open("controller.json", "w") as file:
            json.dump(controller, file, indent=4)
            file.close()
    elif whattodo == "load":
        with open("controller.json", "r") as file:
            data = json.loads(file.read())
            add_missing_units(data, controller)
            controller = get_all_units_from_sys(Loader.syselid)
            delete_wrong_items(data, controller)
            file.close()
    elif whattodo == "del":
        delete_wrong_items({"volumes":[], "luns":[], "qtrees":[]}, controller, True)

class Loader():
    sysname: str = ""
    syselid: str = ""

if __name__ == '__main__':
    @click.command()
    @click.option("--function", "-func", default = "read", required = True, type = str, help = "read/load")
    @click.option("--systorage", "-sys", default = "Netapp-TestLoader", required = True, type = str, help = "SystemStorage name")
    @click.option("--host", "-url", default = "10.161.60.30", required = True, type = str, help = "IP basis")
    @click.option("--port", "-p", default = "8080", required = True, type = str, help = "Port basis")
    @click.option("--protocol", "-pr", default = "http", required = True, type = str, help = "Protocol")
    @click.option("--username", "-un", default = "sdi-api", required = True, type = str, help = "Basis username")
    @click.option("--password", "-pw", default = "basis", required = True, type = str, help = "Basis password")
    @click.option("--mandant", "-man", default = "1001", required = True, type = str, help = "Basis domen")
    @click.option("--group", "-gr", default = "sdi-api", required = True, type = str, help = "Basis group")
    
    def start(function, systorage, host, port, protocol, username, password, mandant, group):
        global basisApi
        basisApi = BasisAPI(url=host,
                        port=port,
                        protocol=protocol,
                        username=username,
                        password=password,
                        mandant=mandant,
                        group=group
                        )
        
        sysElid = basisApi.getStorageSystemByName(systorage)
        Loader.syselid = sysElid
        Loader.sysname = systorage
        func(function)
        print(f'Действие {function} для {systorage} завершено')
    
    start()