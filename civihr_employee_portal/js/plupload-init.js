function Uploader(config)
{
    var that = this;
    this.config = config;
    this.files = {
        list: {},
        deleted: {}
    };
    this.uploaded = false;
    
    for (var i in config.files) {
        var file = config.files[i];
        that.files.list[file.fileID] = {
            id: file.fileID,
            name: file.name,
            size: file.fileSize,
            type: 'existing',
            ob: null
        };
    }
    
    this.uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        drop_element : 'drop-target',
        browse_button : 'pickfiles', // you can pass an id...
        container: document.getElementById('container'), // ... or DOM Element itself
        url : '/civicrm/tasksassignments/file/upload',
        flash_swf_url : '../js/Moxie.swf',
        silverlight_xap_url : '../js/Moxie.xap',
        multipart_params : {
            'entityTable' : 'civicrm_activity',
            'entityID' : that.config.id,
        },

        filters : {
            max_file_size : '10mb',
            mime_types: [
                {title : "Document files", extensions : "doc,docx,pdf,xls"}
            ]
        },

        init: {
            PostInit: function() {
                document.getElementById('notsupported').innerHTML = '';
                document.getElementById('uploadfiles').onclick = function() {
                    uploader.start();
                    return false;
                };
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    that.addFile(file);
                    that.htmlRenderFileList();
                });
            },

            UploadProgress: function(up, file) {
                var el = document.getElementById('status_' + file.id).getElementsByTagName('b')[0];
                el.innerHTML = '<span>' + file.percent + '%</span>';
                if (file.percent == 100) {
                    el.innerHTML = '<span>OK</span>';
                }
            },
            
            FileUploaded: function(up, file) {
                console.info((up.total.uploaded + up.total.failed) + ' / ' + up.files.length);
                if (up.files.length == (up.total.uploaded + up.total.failed)) {
                    console.info('submitting');
                    that.uploaded = true;
                    document.getElementById(that.config.formId).submit();
                }
            },

            Error: function(up, err) {
                console.info("\nError #" + err.code + ": " + err.message);
                document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
    });
    
    this.addFile = function(file) {
        that.files.list[file.id] = {
            id: file.id,
            name: file.name,
            size: file.size,
            type: 'new',
            ob: file
        };
    }
    
    
    this.removeFile = function(id) {
        delete that.files.list[id];
    }
    
    this.remove = function(id) {
        if (that.files.list[id].type == 'existing') {
            that.files.deleted[id] = that.files.list[id];
        } else {
            that.uploader.removeFile(that.files.list[id].ob);
        }
        that.removeFile(id);
        that.htmlRenderFileList();
        console.info('files:');console.info(that.uploader.files);
    }
    
    this.submitForm = function() {
        that.uploader.start();
    }
    
    this.htmlRenderFileList = function() {
        var html = '';
        var index = 1;
        for (var i in files.list) {
            var file = files.list[i];
            html += that.htmlGetFileRow(index++, file.id, file.name, file.size);
        }
        document.getElementById('filelist').innerHTML = html;
    }
    
    this.htmlGetFileRow = function(index, id, name, size) {
        return '<tr>' +
                '    <td><strong class="ng-binding">' + index + '</strong></td>' +
                '    <td><strong class="ng-binding">' + name + '</strong></td>' +
                '    <td nowrap="">' + that.humanFileSize(size, true) + '</td>' +
                '    <td id="status_' + id + '"><b></b></td>' +
                '    <td nowrap="">' +
                '        <button type="button" class="btn btn-danger btn-xs" onclick="uploaderInstance.remove(\'' + id + '\')">' +
                '            <span class="glyphicon glyphicon-trash"></span> Remove' +
                '        </button>' +
                '    </td>' +
                '</tr>';
    }
    
    this.humanFileSize = function(bytes, si) {
        var thresh = si ? 1000 : 1024;
        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }
        var units = si
            ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
            : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
        var u = -1;
        do {
            bytes /= thresh;
            ++u;
        } while (Math.abs(bytes) >= thresh && u < units.length - 1);
        
        return bytes.toFixed(1) + ' ' + units[u];
    }
    
    document.getElementById(that.config.formId).addEventListener('submit', function(e) {
        
        e.preventDefault();
        if (!that.uploaded) {
            console.info('uploading files');
            that.submitForm();
            return false;
        }
        console.info('submitting form');
        return true;
    });
    
    uploader.init();
    htmlRenderFileList();
    
    return this;
}
