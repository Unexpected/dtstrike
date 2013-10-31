package six.challenge.localtester;

import java.awt.BorderLayout;
import java.awt.FlowLayout;

import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JPanel;
import javax.swing.border.EmptyBorder;
import javax.swing.JFileChooser;
import javax.swing.JSplitPane;
import javax.swing.JList;
import javax.swing.JTextField;
import javax.swing.JLabel;

import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.util.*;

import net.miginfocom.swing.MigLayout;

import javax.swing.filechooser.FileFilter;
import javax.swing.filechooser.FileNameExtensionFilter;
import javax.swing.event.ListSelectionListener;
import javax.swing.event.ListSelectionEvent;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;

public class BotLibrary extends JDialog {

	/**
	 * 
	 */
	private static final long serialVersionUID = -1944713769295854253L;
	private final JPanel contentPanel = new JPanel();
	private JTextField botNameField;
	private JTextField exeBot;
	private JList<String> botsToRun;
	private JList<String> availableBots;
	public String[] botList;
	JTextField bot1;
	JTextField bot2;
	JTextField bot3;
	JTextField bot4;
	Properties botLib = new Properties();
	List<String> selectedValues = new ArrayList<String>(4);

	/**
	 * Create the dialog.
	 */
	public BotLibrary() {
		setDefaultCloseOperation(JDialog.DISPOSE_ON_CLOSE);
		try {
			File propFile = new File("bots.xml");
			FileInputStream reader =new FileInputStream(propFile);
			botLib.loadFromXML(reader);
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (InvalidPropertiesFormatException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		setTitle("BotLibrary");
		setBounds(100, 100, 730, 573);
		getContentPane().setLayout(new BorderLayout());
		contentPanel.setBorder(new EmptyBorder(5, 5, 5, 5));
		getContentPane().add(contentPanel, BorderLayout.CENTER);
		contentPanel.setLayout(new BorderLayout(0, 0));
		{
			JSplitPane splitPane = new JSplitPane();
			contentPanel.add(splitPane, BorderLayout.CENTER);
			{
				availableBots = new JList<String>();
				try {
					availableBots.setListData(botLib.keySet().toArray(new String[]{}));
				} catch (Exception e) {
					// TODO Auto-generated catch block
					//e.printStackTrace();
				}
				availableBots.addListSelectionListener(new ListSelectionListener() {
				public void valueChanged(ListSelectionEvent arg0) {
						botNameField.setText(availableBots.getSelectedValue());

						String selected = availableBots.getSelectedValue();
						if (selected!=null) {exeBot.setText(botLib.getProperty(selected));}
						
					}
				});
				availableBots.addMouseListener(new MouseAdapter() {
					@Override
					public void mouseClicked(MouseEvent e) {
						 if (e.getClickCount() == 2) {
								String selected=availableBots.getSelectedValue();
								selectedValues.add(selected);
								botsToRun.setListData(selectedValues.toArray(new String[]{}));
							  }
					}
				});

				splitPane.setLeftComponent(availableBots);
			}
			{
				botsToRun = new JList<String>();
				botsToRun.addMouseListener(new MouseAdapter() {
					@Override
					public void mouseClicked(MouseEvent e) {
						 if (e.getClickCount() == 2) {
							 String selected=botsToRun.getSelectedValue();
								selectedValues.remove(selected);
								botsToRun.setListData(selectedValues.toArray(new String[]{}));
							  }
					}
				});

				splitPane.setRightComponent(botsToRun);
			}
		}
		{
			JPanel panel = new JPanel();
			contentPanel.add(panel, BorderLayout.NORTH);
			panel.setLayout(new MigLayout("", "[77px][383.00px,grow][][5px][89px]", "[25px][][25px]"));
			{
				JLabel lblBotName = new JLabel("Bot Name :");
				panel.add(lblBotName, "cell 0 0,alignx left,aligny center");
			}
			{
				botNameField = new JTextField();
				panel.add(botNameField, "flowx,cell 1 0,alignx left,aligny center");
				botNameField.setColumns(10);
			}
			{
				JLabel lblBotExecutable = new JLabel("Executable");
				panel.add(lblBotExecutable, "cell 0 1,alignx left,aligny center");
			}
			{
				{
					exeBot = new JTextField();
					exeBot.setColumns(10);
					panel.add(exeBot, "cell 1 1,growx,aligny center");
				}
			}
			{
				JButton btnSelectBot = new JButton("Select executable");
				btnSelectBot.setToolTipText("If it's a compiled language, just select the binary to run\nIf it's an (J)VM or interpreted language, the type the exact launch sequence of the bot.\nExample in the java example bots : java -jar xxxx/mybot.jar");
				btnSelectBot.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent arg0) {
						File maps = new File( "sampleBots" );
						JFileChooser chooser = new JFileChooser(maps);
						FileFilter filter = new FileFilter() {

							@Override
							public String getDescription() {
								// TODO Auto-generated method stub
								return null;
							}

							@Override
							public boolean accept(File arg0) {
								return arg0.canExecute();
							}
						};
						chooser.setFileFilter(filter);

						// FileFilter
						int returnVal = chooser.showOpenDialog(botNameField);

						if ((returnVal == JFileChooser.APPROVE_OPTION)) {
							String fullPath=chooser.getSelectedFile().getAbsolutePath();
							exeBot.setText(fullPath);
						}
						
					}
				});
				panel.add(btnSelectBot, "cell 2 1,alignx left,aligny top");
			}
			{
				JButton btnAddBot = new JButton("Update/Add as new Bot");
				btnAddBot.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent arg0) {
						List<String> values = new ArrayList<String>();
						botLib.put(botNameField.getText(), exeBot.getText());
						availableBots.setListData(botLib.keySet().toArray(new String[]{}));
						
						File propFile = new File("bots.xml");
						try {
							FileOutputStream writer =new FileOutputStream(propFile);
							botLib.storeToXML(writer, "");
						} catch (FileNotFoundException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
					}
				});
				panel.add(btnAddBot, "cell 4 1,alignx right,aligny top");
			}
			JButton btnDeleteSelected = new JButton("Delete selected one");
			btnDeleteSelected.addActionListener(new ActionListener() {
				public void actionPerformed(ActionEvent arg0) {
					List<String> values = new ArrayList<String>();
					botLib.remove(botNameField.getText());
					availableBots.setListData(botLib.keySet().toArray(new String[]{}));
					File propFile = new File("bots.xml");
					try {
						FileOutputStream writer =new FileOutputStream(propFile);
						botLib.storeToXML(writer, "");
					} catch (FileNotFoundException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} catch (IOException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
				}
			});
			panel.add(btnDeleteSelected, "cell 4 2,alignx right,aligny top");
		}
		{
			JPanel panel = new JPanel();
			contentPanel.add(panel, BorderLayout.SOUTH);
			{
				JButton button = new JButton(">>");
				button.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent arg0) {
						String selected=availableBots.getSelectedValue();
						selectedValues.add(selected);
						botsToRun.setListData(selectedValues.toArray(new String[]{}));
					}
				});
				panel.add(button);
			}
			{
				JButton button = new JButton("<<");
				button.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent arg0) {
						String selected=botsToRun.getSelectedValue();
						selectedValues.remove(selected);
						botsToRun.setListData(selectedValues.toArray(new String[]{}));
					}
				});
				panel.add(button);
			}
		}
		{
			JPanel buttonPane = new JPanel();
			buttonPane.setLayout(new FlowLayout(FlowLayout.RIGHT));
			getContentPane().add(buttonPane, BorderLayout.SOUTH);
			{
				JButton okButton = new JButton("OK");
				okButton.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent arg0) {
						
						if (selectedValues.size()>0){
						bot1.setText(botLib.getProperty(selectedValues.get(0)));}  
						if (selectedValues.size()>1){
						bot2.setText(botLib.getProperty(selectedValues.get(1)));}  
						if (selectedValues.size()>2){
						bot3.setText(botLib.getProperty(selectedValues.get(2)));} 
						if (selectedValues.size()>3){
						bot4.setText(botLib.getProperty(selectedValues.get(3)));}
						dispose();
					}
				});
				okButton.setActionCommand("OK");
				buttonPane.add(okButton);
				getRootPane().setDefaultButton(okButton);
			}
			{
				JButton cancelButton = new JButton("Cancel");
				cancelButton.addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent e) {
						dispose();
					}
				});
				cancelButton.setActionCommand("Cancel");
				buttonPane.add(cancelButton);
			}
		}
	}

	public void showOpenDialog(JTextField bot1,JTextField bot2,JTextField bot3,JTextField bot4) {
		this.bot1=bot1;
		this.bot2=bot2;
		this.bot3=bot3;
		this.bot4=bot4;
		this.setDefaultCloseOperation(JDialog.DISPOSE_ON_CLOSE);
		
		this.show();
		
	}

}
